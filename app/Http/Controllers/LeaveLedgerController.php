<?php

namespace App\Http\Controllers;

use App\Models\LeaveLedger;
use App\Models\LeaveType;
use App\Models\PDSEmployment;
use App\Models\PDSPersonalData;
use App\Services\LeaveLedgerService;
use App\Support\EmployeeProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LeaveLedgerController extends Controller
{
    /** users.role values allowed to manage ledgers. */
    private const ROLES = ['admin', 'hr'];

    public function __construct(private LeaveLedgerService $ledger) {}

    private function authorizeManage(): void
    {
        abort_unless(auth()->user()?->hasAnyRole(self::ROLES), 403, 'You do not have permission to do this.');
    }

    /** Employees with their current VL / SL balances. */
    public function index(Request $request)
    {
        $this->authorizeManage();

        $employees = PDSPersonalData::query()
            ->when($request->filled('q'), function ($q) use ($request) {
                $t = '%' . $request->q . '%';
                $q->where(fn ($w) => $w->where(EmployeeProfile::SURNAME, 'like', $t)->orWhere(EmployeeProfile::FIRST_NAME, 'like', $t));
            })
            ->orderBy(EmployeeProfile::SURNAME)->orderBy(EmployeeProfile::FIRST_NAME)
            ->paginate(15)->withQueryString();

        $rows = $employees->getCollection();
        $types = LeaveType::where('is_credit_based', true)->where('is_active', true)->orderBy('name')->get();

        $totalEmployees = PDSPersonalData::count();
        $withLedger = LeaveLedger::distinct()->count('emp_id');

        return view('leave-ledgers.index', [
            'employees'   => $employees,
            'types'       => $types,
            'balances'    => $this->ledger->balancesFor($rows->pluck('id')->all(), $types->pluck('id')->all()),
            'employments' => PDSEmployment::with(['department', 'position'])->whereIn('user_id', $rows->pluck('user_id'))->get()->keyBy('user_id'),
            'stats'       => [
                'employees'  => $totalEmployees,
                'entries'    => LeaveLedger::count(),
                'accrued'    => LeaveLedger::where('entry_type', 'earned')->whereDate('period_start', now()->startOfMonth())->distinct()->count('emp_id'),
                'no_ledger'  => max($totalEmployees - $withLedger, 0),
            ],
        ]);
    }

    /** One employee's leave card. */
    public function show(Request $request, PDSPersonalData $employee)
    {
        $this->authorizeManage();

        $types = LeaveType::where('is_active', true)->orderBy('name')->get();
        $tabs  = $types->where('is_credit_based', true)->values();
        $typeId = (int) $request->input('type', $tabs->first()?->id ?? $types->first()?->id);

        $entries = LeaveLedger::with(['recorder', 'reversal'])
            ->where('emp_id', $employee->id)->where('leave_type_id', $typeId)
            ->orderBy('id')->get();

        return view('leave-ledgers.show', [
            'employee'   => $employee,
            'employment' => EmployeeProfile::employment($employee),
            'types'      => $types,
            'tabs'       => $tabs,
            'typeId'     => $typeId,
            'entries'    => $entries,
            'balances'   => $this->ledger->balancesFor([$employee->id], $tabs->pluck('id')->all())[$employee->id] ?? [],
        ]);
    }

    /** Post a manual entry: opening balance, monthly credit, adjustment, monetization, forfeiture. */
    public function store(Request $request, PDSPersonalData $employee)
    {
        $this->authorizeManage();

        $data = $request->validate([
            'leave_type_id'    => ['required', Rule::exists('leave_types', 'id')],
            'entry_type'       => ['required', Rule::in(['opening_balance', 'earned', 'adjustment', 'monetization', 'forfeiture'])],
            'transaction_date' => ['required', 'date'],
            'period_month'     => ['nullable', 'required_if:entry_type,earned', 'date_format:Y-m'],
            'amount'           => ['required', 'numeric', 'gt:0', 'max:9999'],
            'direction'        => ['nullable', 'required_if:entry_type,adjustment', Rule::in(['add', 'deduct'])],
            'particulars'      => ['nullable', 'required_if:entry_type,adjustment', 'string', 'max:255'],
        ]);

        $type   = $data['entry_type'];
        $amount = (float) $data['amount'];
        $adds   = in_array($type, ['opening_balance', 'earned']) || ($type === 'adjustment' && $data['direction'] === 'add');

        $periodStart = $periodEnd = null;
        if ($type === 'earned') {
            $start = Carbon::createFromFormat('Y-m', $data['period_month'])->startOfMonth();
            $periodStart = $start->toDateString();
            $periodEnd   = $start->copy()->endOfMonth()->toDateString();
        } elseif ($type === 'opening_balance') {
            $periodStart = $data['transaction_date'];
        }

        // Friendly message instead of a unique-key error
        if ($periodStart && LeaveLedger::where('emp_id', $employee->id)->where('leave_type_id', $data['leave_type_id'])
                ->where('entry_type', $type)->whereDate('period_start', $periodStart)->exists()) {
            throw ValidationException::withMessages(['entry_type' => $type === 'earned'
                ? 'Credits for that month were already posted.'
                : 'An opening balance already exists for that date.']);
        }

        if (! $adds && $this->ledger->balance($employee->id, (int) $data['leave_type_id']) - $amount < 0) {
            throw ValidationException::withMessages(['amount' => 'This would take the balance below zero.']);
        }

        $default = [
            'opening_balance' => 'Balance forwarded',
            'earned'          => 'Monthly accrual - ' . ($periodStart ? Carbon::parse($periodStart)->format('M Y') : ''),
            'monetization'    => 'Monetization of leave credits',
            'forfeiture'      => 'Forfeiture of leave credits',
        ][$type] ?? null;

        $this->ledger->post([
            'emp_id'           => $employee->id,
            'leave_type_id'    => $data['leave_type_id'],
            'entry_type'       => $type,
            'transaction_date' => $data['transaction_date'],
            'period_start'     => $periodStart,
            'period_end'       => $periodEnd,
            'particulars'      => $data['particulars'] ?: $default,
            'earned'           => $adds ? $amount : 0,
            'days_with_pay'    => $adds ? 0 : $amount,
            'recorded_by'      => auth()->id(),
        ]);

        return redirect()->route('leave-ledgers.show', [$employee, 'type' => $data['leave_type_id']])
            ->with('success', 'Entry posted to the leave ledger.');
    }

    public function reverse(LeaveLedger $entry)
    {
        $this->authorizeManage();
        $this->ledger->reverse($entry, auth()->id());

        return back()->with('success', 'Entry reversed.');
    }

    /** Credit a month of VL / SL to every active employee. */
    public function accrue(Request $request)
    {
        $this->authorizeManage();

        $d = $request->validate([
            'month'     => ['required', 'date_format:Y-m'],
            'vl_amount' => ['required', 'numeric', 'min:0', 'max:31'],
            'sl_amount' => ['required', 'numeric', 'min:0', 'max:31'],
        ]);

        [$posted, $skipped] = $this->ledger->accrue($d['month'], ['VL' => (float) $d['vl_amount'], 'SL' => (float) $d['sl_amount']], auth()->id());

        return back()->with('success', "Monthly accrual posted: {$posted} entries added, {$skipped} already credited and skipped.");
    }
}