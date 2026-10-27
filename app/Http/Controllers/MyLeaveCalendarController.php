<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsLeaveCalendar;
use App\Models\LeaveApplication;
use App\Models\LeaveApplicationDate;
use Illuminate\Http\Request;

/** Employee calendar: read-only, only the employee's own approved leave. */
class MyLeaveCalendarController extends MyLeaveController
{
    use BuildsLeaveCalendar;

    public function index(Request $request)
    {
        $employee = $this->employee();

        $data = $this->calendarData($request, [
            'emp_id'   => $employee->id,
            'statuses' => ['approved'],
            'label'    => 'type',
        ]);
        $c = $data['counts'];

        $own = fn ($q) => $q->where('emp_id', $employee->id)->where('status', 'approved');
        $next = LeaveApplicationDate::with('application.leaveType')
            ->whereDate('leave_date', '>=', today())->whereHas('application', $own)
            ->orderBy('leave_date')->first();

        $takenThisYear = LeaveApplicationDate::whereYear('leave_date', now()->year)->whereHas('application', $own)->count();

        return view('leave-calendar.index', $data + [
            'mode'        => 'self',
            'routeName'   => 'my-leave-calendar.index',
            'title'       => 'My Leave Calendar',
            'subtitle'    => 'Your approved leave, day by day.',
            'crumbs'      => ['My Leave Calendar'],
            'departments' => collect(),
            'leaveTypes'  => collect(),
            'statCards'   => [
                ['label' => 'Leave Days in ' . $data['first']->format('M'), 'value' => $c['days'], 'icon' => 'bi-calendar-check'],
                ['label' => 'Next Leave', 'value' => $next ? $next->leave_date->format('M d') : '—', 'hint' => $next?->application?->leaveType?->name, 'icon' => 'bi-arrow-right-circle'],
                ['label' => 'Leave Days in ' . now()->year, 'value' => $takenThisYear, 'icon' => 'bi-bar-chart'],
                ['label' => 'Pending Requests', 'value' => LeaveApplication::where('emp_id', $employee->id)->whereIn('status', ['pending', 'certified', 'recommended'])->count(), 'icon' => 'bi-hourglass-split'],
            ],
        ]);
    }
}