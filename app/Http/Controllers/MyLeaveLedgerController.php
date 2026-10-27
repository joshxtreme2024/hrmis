<?php

namespace App\Http\Controllers;

use App\Models\LeaveLedger;
use App\Models\LeaveType;
use App\Services\LeaveLedgerService;
use Illuminate\Http\Request;

/**
 * Employee self-service, read-only: an employee sees only their own leave card.
 * Extends MyLeaveController so the "which employee is logged in" logic lives in one place.
 */
class MyLeaveLedgerController extends MyLeaveController
{
    public function __construct(private LeaveLedgerService $ledger) {}

    public function index(Request $request)
    {
        $employee = $this->employee();

        // Vacation / Sick Leave always, plus any other type this employee has entries for
        $usedTypeIds = LeaveLedger::where('emp_id', $employee->id)->distinct()->pluck('leave_type_id');
        $types = LeaveType::where(fn ($q) => $q->where('is_credit_based', true)->where('is_active', true)->orWhereIn('id', $usedTypeIds))
            ->orderBy('name')->get();

        $typeId = (int) $request->input('type');
        if (! $types->contains('id', $typeId)) {
            $typeId = (int) ($types->first()?->id);
        }

        $all = LeaveLedger::with('reversal')
            ->where('emp_id', $employee->id)->where('leave_type_id', $typeId)
            ->orderBy('id')->get();

        $years = $all->map(fn ($e) => $e->transaction_date->year)->unique()->sortDesc()->values();
        $year  = $request->filled('year') && $years->contains((int) $request->year) ? (int) $request->year : null;

        // When one year is selected, show the balance carried in from earlier years
        $brought = null;
        $entries = $all;
        if ($year) {
            $brought = $all->filter(fn ($e) => $e->transaction_date->year < $year)->last()?->balance;
            $entries = $all->filter(fn ($e) => $e->transaction_date->year === $year)->values();
        }

        return view('my-leave-ledger.index', [
            'types'    => $types,
            'typeId'   => $typeId,
            'years'    => $years,
            'year'     => $year,
            'entries'  => $entries,
            'brought'  => $brought,
            'balances' => $this->ledger->balancesFor([$employee->id], $types->pluck('id')->all())[$employee->id] ?? [],
        ]);
    }
}