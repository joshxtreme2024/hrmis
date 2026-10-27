<?php

namespace App\Http\Controllers;

use App\Models\PDSPersonalData;
use App\Support\EmployeeProfile;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Employee self-service: an employee sees and files only their own leave applications.
 * Reuses the validation / overlap checks of LeaveApplicationController::prepare().
 */
class MyLeaveController extends LeaveApplicationController
{
    /** The employee record of the logged-in user. Adjust this one method if your link differs. */
    protected function employee(): PDSPersonalData
    {
        $employee = auth()->user()->personalData ?? null;   // User -> PDSPersonalData (via user_id)
        abort_if(! $employee, 403, 'Your account is not linked to an employee record. Please contact HR.');

        return $employee;
    }

    protected function owned(LeaveApplication $application): LeaveApplication
    {
        abort_unless((int) $application->emp_id === (int) $this->employee()->id, 404);

        return $application;
    }

    /** Applicant details always come from the employee record, never from the form. */
    protected function identity(PDSPersonalData $e, string $filingDate): array
    {
        $employment = EmployeeProfile::employment($e);

        return [
            'emp_id'         => $e->id,
            'department_id'  => EmployeeProfile::departmentId($e, $employment),
            'position'       => EmployeeProfile::position($e, $employment) ?: 'Not specified',
            'salary'         => EmployeeProfile::salary($e, $employment),
            'date_of_filing' => $filingDate,
        ];
    }

    /** VL / SL balances keyed by code: earned, used, balance, as_of. */
    protected function balances(int $employeeId): array
    {
        $rows = DB::table('leave_credits as c')
            ->join('leave_types as t', 't.id', '=', 'c.leave_type_id')
            ->where('c.emp_id', $employeeId)->whereIn('t.code', ['VL', 'SL'])
            ->get(['t.code', 'c.total_earned', 'c.total_used', 'c.as_of_date'])->keyBy('code');

        $out = [];
        foreach (['VL' => 'Vacation Leave', 'SL' => 'Sick Leave'] as $code => $name) {
            $r = $rows[$code] ?? null;
            $out[$code] = [
                'name'    => $name,
                'earned'  => $r ? (float) $r->total_earned : null,
                'used'    => $r ? (float) $r->total_used : null,
                'balance' => $r ? round($r->total_earned - $r->total_used, 3) : null,
                'as_of'   => $r?->as_of_date,
            ];
        }

        return $out;
    }

    public function index(Request $request)
    {
        $employee = $this->employee();

        $applications = LeaveApplication::with('leaveType')
            ->where('emp_id', $employee->id)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest('date_of_filing')->latest('id')
            ->paginate(10)->withQueryString();

        return view('my-leaves.index', [
            'applications' => $applications,
            'balances'     => $this->balances($employee->id),
            'counts'       => LeaveApplication::where('emp_id', $employee->id)
                                ->selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status'),
        ]);
    }

    public function create()
    {
        $employee = $this->employee();

        return view('my-leaves.form', [
            'application' => new LeaveApplication(['date_of_filing' => now()]),
            'employee'    => $employee,
            'department'  => EmployeeProfile::employment($employee)?->department,
            'balances'    => $this->balances($employee->id),
            'leaveTypes'  => LeaveType::active()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->merge($this->identity($this->employee(), now()->toDateString()));
        [$data, $dates] = $this->prepare($request);

        $application = DB::transaction(function () use ($data, $dates) {
            $app = LeaveApplication::create($data + ['status' => 'pending']);
            $app->dates()->createMany($dates->map(fn ($d) => ['leave_date' => $d])->all());
            return $app;
        });

        return redirect()->route('my-leaves.show', $application)
            ->with('success', 'Your leave application was submitted. HR will review it.');
    }

    public function show(LeaveApplication $leaveApplication)
    {
        $a = $this->owned($leaveApplication)->load(['leaveType', 'dates']);

        return view('my-leaves.show', ['a' => $a]);
    }

    public function edit(LeaveApplication $leaveApplication)
    {
        $a = $this->owned($leaveApplication);
        abort_unless($a->isEditable(), 403, 'Only pending applications can be edited.');
        $employee = $this->employee();

        return view('my-leaves.form', [
            'application' => $a,
            'employee'    => $employee,
            'department'  => EmployeeProfile::employment($employee)?->department,
            'balances'    => $this->balances($employee->id),
            'leaveTypes'  => LeaveType::active()->orWhere('id', $a->leave_type_id)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, LeaveApplication $leaveApplication)
    {
        $a = $this->owned($leaveApplication);
        abort_unless($a->isEditable(), 403);

        $request->merge($this->identity($this->employee(), $a->date_of_filing->toDateString()));
        [$data, $dates] = $this->prepare($request, $a->id);

        DB::transaction(function () use ($a, $data, $dates) {
            $a->update($data);
            $a->dates()->delete();
            $a->dates()->createMany($dates->map(fn ($d) => ['leave_date' => $d])->all());
        });

        return redirect()->route('my-leaves.show', $a)->with('success', 'Your application was updated.');
    }

    public function cancel(LeaveApplication $leaveApplication)
    {
        $this->owned($leaveApplication);

        return parent::cancel($leaveApplication);
    }

    /** Printable CS Form No. 6 for an approved application. */
    public function printForm(LeaveApplication $leaveApplication)
    {
        $a = $this->owned($leaveApplication);
        abort_unless($a->status === 'approved', 403, 'Only approved applications can be printed.');

        $employee = $this->employee();

        return view('my-leaves.print', [
            'a'     => $a->load(['department', 'leaveType']),
            'name'  => EmployeeProfile::nameParts($employee),
        ]);
    }
}