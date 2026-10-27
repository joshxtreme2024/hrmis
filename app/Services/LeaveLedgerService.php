<?php

namespace App\Services;

use App\Models\LeaveApplication;
use App\Models\LeaveLedger;
use App\Models\LeaveType;
use App\Models\PDSPersonalData;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * The only place that writes to leave_ledgers.
 * Every post keeps the running balance correct and keeps the leave_credits summary in step.
 */
class LeaveLedgerService
{
    /** Current balance: last ledger row, or the existing leave_credits summary if the ledger is still empty. */
    public function balance(int $empId, int $typeId, bool $lock = false): float
    {
        $last = LeaveLedger::where('emp_id', $empId)->where('leave_type_id', $typeId)
            ->orderByDesc('id')->when($lock, fn ($q) => $q->lockForUpdate())->first();

        if ($last) return (float) $last->balance;

        $c = DB::table('leave_credits')->where('emp_id', $empId)->where('leave_type_id', $typeId)->first();

        return $c ? round($c->total_earned - $c->total_used, 3) : 0.0;
    }

    /** [empId => [typeId => balance]] for a page of employees, in two queries. */
    public function balancesFor(array $empIds, array $typeIds): array
    {
        $out = [];
        if (! $empIds || ! $typeIds) return $out;

        $lastIds = DB::table('leave_ledgers')->whereIn('emp_id', $empIds)->whereIn('leave_type_id', $typeIds)
            ->selectRaw('max(id) as id')->groupBy('emp_id', 'leave_type_id')->pluck('id');

        foreach (DB::table('leave_ledgers')->whereIn('id', $lastIds)->get(['emp_id', 'leave_type_id', 'balance']) as $r) {
            $out[$r->emp_id][$r->leave_type_id] = (float) $r->balance;
        }

        foreach (DB::table('leave_credits')->whereIn('emp_id', $empIds)->whereIn('leave_type_id', $typeIds)->get() as $c) {
            $out[$c->emp_id][$c->leave_type_id] ??= round($c->total_earned - $c->total_used, 3);
        }

        return $out;
    }

    /** Append one entry. $a needs emp_id, leave_type_id, entry_type, transaction_date; amounts default to 0. */
    public function post(array $a): LeaveLedger
    {
        return DB::transaction(function () use ($a) {
            $earned = (float) ($a['earned'] ?? 0);
            $with   = (float) ($a['days_with_pay'] ?? 0);

            $balance = round($this->balance($a['emp_id'], $a['leave_type_id'], true) + $earned - $with, 3);

            $entry = LeaveLedger::create($a + ['days_without_pay' => 0] + [
                'earned' => $earned, 'days_with_pay' => $with, 'balance' => $balance,
            ]);

            $this->syncCredits($entry);

            return $entry;
        });
    }

    /** Called when the Mayor approves a credit-based leave (Vacation / Sick). */
    public function postUsage(LeaveApplication $app): LeaveLedger
    {
        $days = rtrim(rtrim(number_format($app->working_days, 2), '0'), '.');

        return $this->post([
            'emp_id'               => $app->emp_id,
            'leave_type_id'        => $app->leave_type_id,
            'leave_application_id' => $app->id,
            'entry_type'           => 'used',
            'transaction_date'     => now()->toDateString(),
            'period_start'         => $app->date_from->toDateString(),
            'period_end'           => $app->date_to->toDateString(),
            'particulars'          => $app->leaveType->name . ' ' . $days . ' day(s)',
            'days_with_pay'        => (float) $app->approved_days_with_pay,
            'days_without_pay'     => (float) ($app->approved_days_without_pay ?? 0),
            'action_taken'         => 'Approved ' . now()->format('m/d/Y'),
            'recorded_by'          => auth()->id(),
        ]);
    }

    /** Cancel an entry by posting its opposite. */
    public function reverse(LeaveLedger $entry, ?int $userId = null): LeaveLedger
    {
        return DB::transaction(function () use ($entry, $userId) {
            $entry = LeaveLedger::lockForUpdate()->findOrFail($entry->id);

            abort_if(
                $entry->entry_type === 'reversal' || LeaveLedger::where('reverses_entry_id', $entry->id)->exists(),
                422, 'This entry cannot be reversed.'
            );

            return $this->post([
                'emp_id'               => $entry->emp_id,
                'leave_type_id'        => $entry->leave_type_id,
                'leave_application_id' => $entry->leave_application_id,
                'reverses_entry_id'    => $entry->id,
                'entry_type'           => 'reversal',
                'transaction_date'     => now()->toDateString(),
                'particulars'          => 'Reversal: ' . ($entry->particulars ?: $entry->typeLabel()),
                'earned'               => $entry->days_with_pay,   // gives back what was deducted
                'days_with_pay'        => $entry->earned,          // takes back what was added
                'recorded_by'          => $userId,
            ]);
        });
    }

    /**
     * Monthly accrual for every active employee. $rates = ['VL' => 1.25, 'SL' => 1.25].
     * Employees already credited for that month are skipped. Returns [posted, skipped].
     */
    public function accrue(string $month, array $rates, ?int $userId = null): array
    {
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $types = LeaveType::whereIn('code', array_keys($rates))->get()->keyBy('code');
        $employees = PDSPersonalData::whereIn('user_id', User::where('status', 'active')->pluck('id'))->get();

        $posted = $skipped = 0;

        foreach ($employees as $emp) {
            foreach ($rates as $code => $amount) {
                $type = $types[$code] ?? null;
                if (! $type || $amount <= 0) continue;

                $exists = LeaveLedger::where('emp_id', $emp->id)->where('leave_type_id', $type->id)
                    ->where('entry_type', 'earned')->whereDate('period_start', $start)->exists();

                if ($exists) { $skipped++; continue; }

                $this->post([
                    'emp_id'           => $emp->id,
                    'leave_type_id'    => $type->id,
                    'entry_type'       => 'earned',
                    'transaction_date' => now()->toDateString(),
                    'period_start'     => $start->toDateString(),
                    'period_end'       => $start->copy()->endOfMonth()->toDateString(),
                    'particulars'      => 'Monthly accrual - ' . $start->format('M Y'),
                    'earned'           => $amount,
                    'recorded_by'      => $userId,
                ]);
                $posted++;
            }
        }

        return [$posted, $skipped];
    }

    /** Keep the leave_credits summary in step (incrementally, so pre-existing credits are never overwritten). */
    private function syncCredits(LeaveLedger $e): void
    {
        $row = DB::table('leave_credits')->where('emp_id', $e->emp_id)->where('leave_type_id', $e->leave_type_id);

        if ($row->exists()) {
            $row->update([
                'total_earned' => DB::raw('total_earned + ' . (float) $e->earned),
                'total_used'   => DB::raw('total_used + ' . (float) $e->days_with_pay),
                'as_of_date'   => $e->transaction_date->toDateString(),
                'updated_at'   => now(),
            ]);
        } else {
            DB::table('leave_credits')->insert([
                'emp_id' => $e->emp_id, 'leave_type_id' => $e->leave_type_id,
                'total_earned' => $e->earned, 'total_used' => $e->days_with_pay,
                'as_of_date' => $e->transaction_date->toDateString(),
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }
}