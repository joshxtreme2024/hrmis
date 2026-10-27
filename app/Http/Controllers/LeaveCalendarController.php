<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsLeaveCalendar;
use App\Models\Departments;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use Illuminate\Http\Request;

/** HR calendar: everyone's leave, all offices. */
class LeaveCalendarController extends Controller
{
    use BuildsLeaveCalendar;

    private const ROLES = ['admin', 'hr'];

    public function index(Request $request)
    {
        abort_unless(auth()->user()?->hasAnyRole(self::ROLES), 403, 'You do not have permission to do this.');

        $data = $this->calendarData($request, ['link' => true]);
        $c = $data['counts'];

        return view('leave-calendar.index', $data + [
            'mode'        => 'hr',
            'routeName'   => 'leave-calendar.index',
            'title'       => 'Leave Calendar',
            'subtitle'    => 'See who is on leave, day by day.',
            'crumbs'      => ['Leave Management', 'Leave Calendar'],
            'departments' => Departments::orderBy('name')->get(),
            'leaveTypes'  => LeaveType::where('is_active', true)->orderBy('name')->get(),
            'statCards'   => [
                ['label' => 'On Leave Today',    'value' => $c['today'], 'icon' => 'bi-person-walking'],
                ['label' => 'Employees Out in ' . $data['first']->format('M'), 'value' => $c['out'], 'icon' => 'bi-people'],
                ['label' => 'Approved Leave Days', 'value' => $c['days'], 'hint' => 'in ' . $data['first']->format('F Y'), 'icon' => 'bi-calendar-check'],
                ['label' => 'Awaiting Decision', 'value' => LeaveApplication::whereIn('status', ['pending', 'certified', 'recommended'])->count(), 'icon' => 'bi-hourglass-split'],
            ],
        ]);
    }
}