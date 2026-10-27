<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsLeaveCalendar;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use Illuminate\Http\Request;

/** Department-head calendar: only the head's own office. */
class DepartmentLeaveCalendarController extends Controller
{
    use BuildsLeaveCalendar;

    /** users.role values that count as a department head. Edit to match your roles. */
    private const ROLES = ['department_head', 'admin', 'hr'];

    public function index(Request $request)
    {
        $user = auth()->user();
        abort_unless($user?->hasAnyRole(self::ROLES), 403, 'You do not have permission to do this.');

        // The head's own office, from their employment record
        $department = $user->employment?->department;
        abort_if(! $department, 403, 'Your account is not assigned to an office/department. Please contact HR.');

        $data = $this->calendarData($request, ['department_id' => $department->id]);
        $c = $data['counts'];

        return view('leave-calendar.index', $data + [
            'mode'        => 'dept',
            'routeName'   => 'department-leave-calendar.index',
            'title'       => $department->name . ' Leave Calendar',
            'subtitle'    => 'Leave of the employees in your office.',
            'crumbs'      => ['Department Leave Calendar'],
            'departments' => collect(),
            'leaveTypes'  => LeaveType::where('is_active', true)->orderBy('name')->get(),
            'statCards'   => [
                ['label' => 'On Leave Today',    'value' => $c['today'], 'icon' => 'bi-person-walking'],
                ['label' => 'Employees Out in ' . $data['first']->format('M'), 'value' => $c['out'], 'icon' => 'bi-people'],
                ['label' => 'Approved Leave Days', 'value' => $c['days'], 'hint' => 'in ' . $data['first']->format('F Y'), 'icon' => 'bi-calendar-check'],
                ['label' => 'Awaiting Decision', 'value' => LeaveApplication::where('department_id', $department->id)->whereIn('status', ['pending', 'certified', 'recommended'])->count(), 'icon' => 'bi-hourglass-split'],
            ],
        ]);
    }
}