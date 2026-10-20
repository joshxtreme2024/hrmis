<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\PDSEmployment;
use App\Models\PDSPersonalData;
use App\Support\EmployeeProfile;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LeaveApplicationController extends Controller
{
    /** Statuses that block the same dates from being applied for again. */
    private const LIVE = ['pending', 'certified', 'recommended', 'approved'];

    /** Which values of users.role may do what. Edit these lists to match your roles. */
    protected const MANAGE_ROLES    = ['admin', 'hr'];   // see / file / edit / delete any leave application
    protected const CERTIFY_ROLES   = ['admin', 'hr'];   // 7.A certification of leave credits
    protected const RECOMMEND_ROLES = ['admin', 'hr'];   // 7.B recommendation
    protected const DECIDE_ROLES    = ['admin', 'hr'];         // 7.C / 7.D approval (Municipal Mayor)

    protected function authorizeRoles(array $roles): void
    {
        abort_unless(auth()->user()?->hasAnyRole($roles), 403, 'You do not have permission to do this.');
    }

    public function index(Request $request)
    {
        $this->authorizeRoles(self::MANAGE_ROLES);
        $applications = LeaveApplication::with(['employee', 'leaveType', 'department'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('leave_type_id'), fn ($q) => $q->where('leave_type_id', $request->leave_type_id))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('date_from', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('date_to', '<=', $request->to))
            ->when($request->filled('q'), function ($q) use ($request) {
                $t = '%' . $request->q . '%';
                $q->whereHas('employee', fn ($e) => $e->where(EmployeeProfile::FIRST_NAME, 'like', $t)->orWhere(EmployeeProfile::SURNAME, 'like', $t));
            })
            ->latest('date_of_filing')->latest('id')
            ->paginate(15)->withQueryString();

        return view('leave-applications.index', [
            'applications' => $applications,
            'leaveTypes'   => LeaveType::orderBy('name')->get(),
            'counts'       => LeaveApplication::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status'),
        ]);
    }

    public function create()
    {
        $this->authorizeRoles(self::MANAGE_ROLES);
        return view('leave-applications.form', $this->formData(new LeaveApplication(['date_of_filing' => now()])));
    }

    public function store(Request $request)
    {
        $this->authorizeRoles(self::MANAGE_ROLES);
        [$data, $dates] = $this->prepare($request);

        $application = DB::transaction(function () use ($data, $dates) {
            $app = LeaveApplication::create($data + ['status' => 'pending']);
            $app->dates()->createMany($dates->map(fn ($d) => ['leave_date' => $d])->all());
            return $app;
        });

        return redirect()->route('leave-applications.show', $application)->with('success', 'Leave application filed.');
    }

    public function show(LeaveApplication $leaveApplication)
    {
        $this->authorizeRoles(self::MANAGE_ROLES);
        $a = $leaveApplication->load(['employee', 'department', 'leaveType', 'dates', 'certifier', 'recommender', 'approver']);

        // Current VL / SL balances (earned - used) to prefill 7.A
        $credits = DB::table('leave_credits as c')
            ->join('leave_types as t', 't.id', '=', 'c.leave_type_id')
            ->where('c.emp_id', $a->emp_id)->whereIn('t.code', ['VL', 'SL'])
            ->get(['t.code', 'c.total_earned', 'c.total_used', 'c.as_of_date'])->keyBy('code');
        $bal = fn ($code) => isset($credits[$code]) ? round($credits[$code]->total_earned - $credits[$code]->total_used, 3) : 0;

        return view('leave-applications.show', [
            'a'         => $a,
            'vlBalance' => $bal('VL'),
            'slBalance' => $bal('SL'),
            'creditsAsOf' => optional($credits->first())->as_of_date,
            'can'       => [
                'certify'   => (bool) auth()->user()?->hasAnyRole(self::CERTIFY_ROLES),
                'recommend' => (bool) auth()->user()?->hasAnyRole(self::RECOMMEND_ROLES),
                'decide'    => (bool) auth()->user()?->hasAnyRole(self::DECIDE_ROLES),
            ],
        ]);
    }

    public function edit(LeaveApplication $leaveApplication)
    {
        $this->authorizeRoles(self::MANAGE_ROLES);
        abort_unless($leaveApplication->isEditable(), 403, 'Only draft or pending applications can be edited.');

        return view('leave-applications.form', $this->formData($leaveApplication));
    }

    public function update(Request $request, LeaveApplication $leaveApplication)
    {
        $this->authorizeRoles(self::MANAGE_ROLES);
        abort_unless($leaveApplication->isEditable(), 403);
        [$data, $dates] = $this->prepare($request, $leaveApplication->id);

        DB::transaction(function () use ($leaveApplication, $data, $dates) {
            $leaveApplication->update($data);
            $leaveApplication->dates()->delete();
            $leaveApplication->dates()->createMany($dates->map(fn ($d) => ['leave_date' => $d])->all());
        });

        return redirect()->route('leave-applications.show', $leaveApplication)->with('success', 'Leave application updated.');
    }

    public function destroy(LeaveApplication $leaveApplication)
    {
        $this->authorizeRoles(self::MANAGE_ROLES);
        abort_unless($leaveApplication->isDeletable(), 403);
        $leaveApplication->delete();

        return redirect()->route('leave-applications.index')->with('success', 'Leave application deleted.');
    }

    /* ================= Workflow ================= */

    /** 7.A Certification of leave credits (HR) */
    public function certify(Request $request, LeaveApplication $leaveApplication)
    {
        $this->authorizeRoles(self::CERTIFY_ROLES);
        abort_unless($leaveApplication->status === 'pending', 403);

        $d = $request->validate([
            'credits_as_of'            => ['required', 'date'],
            'vl_total_earned'          => ['required', 'numeric', 'min:0'],
            'sl_total_earned'          => ['required', 'numeric', 'min:0'],
            'vl_less_this_application' => ['required', 'numeric', 'min:0'],
            'sl_less_this_application' => ['required', 'numeric', 'min:0'],
        ]);
        $d['vl_balance'] = $d['vl_total_earned'] - $d['vl_less_this_application'];
        $d['sl_balance'] = $d['sl_total_earned'] - $d['sl_less_this_application'];

        $leaveApplication->update($d + ['status' => 'certified', 'certified_by' => auth()->id(), 'certified_at' => now()]);

        return back()->with('success', 'Leave credits certified.');
    }

    /** 7.B Recommendation */
    public function recommend(Request $request, LeaveApplication $leaveApplication)
    {
        $this->authorizeRoles(self::RECOMMEND_ROLES);
        abort_unless($leaveApplication->status === 'certified', 403);

        $d = $request->validate([
            'recommendation' => ['required', Rule::in(['for_approval', 'for_disapproval'])],
            'recommendation_disapproval_reason' => ['required_if:recommendation,for_disapproval', 'nullable', 'string', 'max:1000'],
        ]);
        if ($d['recommendation'] === 'for_approval') $d['recommendation_disapproval_reason'] = null;

        $leaveApplication->update($d + ['status' => 'recommended', 'recommended_by' => auth()->id(), 'recommended_at' => now()]);

        return back()->with('success', 'Recommendation recorded.');
    }

    /** 7.C Approved for / 7.D Disapproved due to (Municipal Mayor) */
    public function decide(Request $request, LeaveApplication $leaveApplication)
    {
        $this->authorizeRoles(self::DECIDE_ROLES);
        $request->validate(['decision' => ['required', Rule::in(['approve', 'disapprove'])]]);

        if ($request->decision === 'approve') {
            $d = $request->validate([
                'approved_days_with_pay'    => ['required', 'numeric', 'min:0'],
                'approved_days_without_pay' => ['nullable', 'numeric', 'min:0'],
                'approved_days_others'      => ['nullable', 'numeric', 'min:0'],
                'approved_others_specify'   => ['nullable', 'string', 'max:255'],
            ]);
            $d += ['status' => 'approved', 'disapproval_reason' => null];
        } else {
            $d = $request->validate(['disapproval_reason' => ['required', 'string', 'max:1000']]);
            $d += ['status' => 'disapproved'];
        }

        DB::transaction(function () use ($leaveApplication, $d) {
            $app = LeaveApplication::with('leaveType')->lockForUpdate()->findOrFail($leaveApplication->id);
            abort_unless($app->status === 'recommended', 403);

            $app->update($d + ['approved_by' => auth()->id(), 'decided_at' => now()]);

            // Deduct paid days from the employee's VL / SL credits
            if ($d['status'] === 'approved' && $app->leaveType->is_credit_based && ($d['approved_days_with_pay'] ?? 0) > 0) {
                DB::table('leave_credits')
                    ->where('emp_id', $app->emp_id)->where('leave_type_id', $app->leave_type_id)
                    ->increment('total_used', $d['approved_days_with_pay']);
            }
        });

        return back()->with('success', $d['status'] === 'approved' ? 'Application approved.' : 'Application disapproved.');
    }

    public function staffCancel(LeaveApplication $leaveApplication)
    {
        $this->authorizeRoles(self::MANAGE_ROLES);

        return $this->cancel($leaveApplication);
    }

    public function cancel(LeaveApplication $leaveApplication)
    {
        abort_unless($leaveApplication->isCancellable(), 403);
        $leaveApplication->update(['status' => 'cancelled']);

        return back()->with('success', 'Application cancelled.');
    }

    /* ================= Helpers ================= */

    private function formData(LeaveApplication $application): array
    {
        return [
            'application' => $application,
            'leaveTypes'  => LeaveType::active()->orWhere('id', $application->leave_type_id)->orderBy('name')->get(),
            'departments' => Departments::orderBy('name')->get(),
            // Name / department / position come from the PDS + employment record (see App\Support\EmployeeProfile).
            'employees'   => $this->employeeOptions(),
        ];
    }

    private function employeeOptions()
    {
        $employments = PDSEmployment::with(['department', 'position'])->get()->keyBy('user_id');

        return PDSPersonalData::get()->map(function ($e) use ($employments) {
            $emp = $employments[$e->user_id] ?? null;

            return [
                'id'            => $e->id,
                'name'          => EmployeeProfile::name($e),
                'department_id' => $emp?->department?->id,
                'position'      => $emp?->position?->title,
                'salary'        => data_get($emp, EmployeeProfile::SALARY),
            ];
        })->sortBy('name')->values();
    }

    /** Validate, clear fields that don't apply to the chosen leave type, build the day list, check overlaps. */
    protected function prepare(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'emp_id'               => ['required', Rule::exists((new PDSPersonalData)->getTable(), 'id')],
            'department_id'             => ['nullable', 'exists:departments,id'],
            'date_of_filing'            => ['required', 'date'],
            'position'                  => ['required', 'string', 'max:255'],
            'salary'                    => ['nullable', 'numeric', 'min:0'],
            'leave_type_id'             => ['required', 'exists:leave_types,id'],
            'leave_type_other'          => ['nullable', 'string', 'max:255'],
            'vacation_location'         => ['nullable', Rule::in(['within_philippines', 'abroad'])],
            'vacation_location_specify' => ['nullable', 'string', 'max:255'],
            'sick_setting'              => ['nullable', Rule::in(['in_hospital', 'out_patient'])],
            'illness_specify'           => ['nullable', 'string', 'max:255'],
            'study_purpose'             => ['nullable', Rule::in(['masters_degree', 'bar_board_review'])],
            'other_purpose'             => ['nullable', Rule::in(['monetization', 'terminal_leave'])],
            'working_days'              => ['required', 'numeric', 'min:0.5', 'max:365'],
            'date_from'                 => ['required', 'date'],
            'date_to'                   => ['required', 'date', 'after_or_equal:date_from'],
        ]);
        $data['commutation_requested'] = $request->boolean('commutation_requested');

        $code = LeaveType::findOrFail($data['leave_type_id'])->code;

        if ($code === 'OTHER' && blank($data['leave_type_other'] ?? null)) {
            throw ValidationException::withMessages(['leave_type_other' => 'Please specify the type of leave.']);
        }
        if (! in_array($code, ['VL', 'SPL'])) { $data['vacation_location'] = $data['vacation_location_specify'] = null; }
        if ($code !== 'SL')                    { $data['sick_setting'] = null; }
        if (! in_array($code, ['SL', 'SLBW'])) { $data['illness_specify'] = null; }
        if ($code !== 'STUDY')                 { $data['study_purpose'] = null; }
        if ($code !== 'OTHER')                 { $data['leave_type_other'] = null; }

        // One row per weekday in the range
        $dates = collect(CarbonPeriod::create($data['date_from'], $data['date_to']))
            ->reject(fn ($d) => $d->isWeekend())->map(fn ($d) => $d->toDateString())->values();

        if ($dates->isEmpty()) {
            throw ValidationException::withMessages(['date_from' => 'The selected range has no working days (weekdays only).']);
        }

        $overlap = DB::table('leave_application_dates as d')
            ->join('leave_applications as a', 'a.id', '=', 'd.leave_application_id')
            ->where('a.emp_id', $data['emp_id'])->whereNull('a.deleted_at')
            ->whereIn('a.status', self::LIVE)
            ->when($ignoreId, fn ($q) => $q->where('a.id', '!=', $ignoreId))
            ->whereIn('d.leave_date', $dates)->exists();

        if ($overlap) {
            throw ValidationException::withMessages(['date_from' => 'This employee already has a leave application covering some of these dates.']);
        }

        return [$data, $dates];
    }
}