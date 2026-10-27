<?php

namespace App\Http\Controllers\Concerns;

use App\Models\LeaveApplicationDate;
use App\Support\EmployeeProfile;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

/**
 * Builds the data for the month calendar. Shared by the HR, department-head and employee calendars.
 *
 * Options ($o):
 *   emp_id        limit to one employee (employee calendar)
 *   department_id limit to one office (department-head calendar)
 *   statuses      force the statuses shown (e.g. ['approved']); otherwise ?show=all adds pending ones
 *   link          chips/popup link to the HR application page (HR only)
 *   label         'name' = employee short name on the chip, 'type' = leave type name
 */
trait BuildsLeaveCalendar
{
    protected function calendarData(Request $request, array $o = []): array
    {
        $o += ['emp_id' => null, 'department_id' => null, 'statuses' => null, 'link' => false, 'label' => 'name'];

        $month = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', (string) $request->month) ? $request->month : now()->format('Y-m');
        $first = Carbon::createFromFormat('Y-m-d', $month . '-01')->startOfDay();
        $last  = $first->copy()->endOfMonth();

        $gridStart = $first->copy()->startOfWeek(Carbon::SUNDAY);
        $gridEnd   = $last->copy()->endOfWeek(Carbon::SATURDAY);

        $statuses = $o['statuses'] ?? ($request->input('show') === 'all'
            ? ['approved', 'pending', 'certified', 'recommended']
            : ['approved']);

        // The fixed part of the scope (who the viewer is allowed to see)
        $scoped = fn ($q) => $q
            ->when($o['emp_id'], fn ($q) => $q->where('emp_id', $o['emp_id']))
            ->when($o['department_id'], fn ($q) => $q->where('department_id', $o['department_id']));

        $rows = LeaveApplicationDate::with(['application.leaveType', 'application.employee', 'application.department'])
            ->whereBetween('leave_date', [$gridStart->toDateString(), $gridEnd->toDateString()])
            ->whereHas('application', function ($q) use ($request, $statuses, $o, $scoped) {
                $q->whereIn('status', $statuses);
                $scoped($q);
                $q->when(! $o['department_id'] && ! $o['emp_id'] && $request->filled('department_id'), fn ($q) => $q->where('department_id', $request->department_id))
                  ->when($request->filled('leave_type_id'), fn ($q) => $q->where('leave_type_id', $request->leave_type_id))
                  ->when(! $o['emp_id'] && $request->filled('q'), function ($q) use ($request) {
                      $t = '%' . $request->q . '%';
                      $q->whereHas('employee', fn ($e) => $e->where(EmployeeProfile::SURNAME, 'like', $t)->orWhere(EmployeeProfile::FIRST_NAME, 'like', $t));
                  });
            })
            ->orderBy('leave_date')->get();

        $byDay = [];
        foreach ($rows as $r) {
            $a = $r->application;
            $p = EmployeeProfile::nameParts($a->employee);
            $short = $p[1] !== '' ? $p[0] . ', ' . mb_substr($p[1], 0, 1) . '.' : $p[0];

            $byDay[$r->leave_date->toDateString()][] = [
                'id'           => $a->id,
                'emp_id'       => $a->emp_id,
                'name'         => $a->employeeName(),
                'label'        => $o['label'] === 'type' ? $a->leaveType->name : $short,
                'code'         => $a->leaveType->code,
                'type'         => $a->leaveType->name,
                'status'       => $a->status,
                'status_label' => $a->statusLabel(),
                'dept'         => $a->department?->name,
                'range'        => $a->date_from->eq($a->date_to) ? $a->date_from->format('M d, Y') : $a->date_from->format('M d') . ' – ' . $a->date_to->format('M d, Y'),
                'days'         => rtrim(rtrim(number_format($a->working_days, 2), '0'), '.'),
                'half'         => (float) $r->day_fraction < 1,
                'url'          => $o['link'] ? route('leave-applications.show', $a) : null,
            ];
        }
        foreach ($byDay as &$list) {
            usort($list, fn ($x, $y) => [$x['status'] !== 'approved', $x['name']] <=> [$y['status'] !== 'approved', $y['name']]);
        }
        unset($list);

        $inMonth = collect($byDay)->filter(fn ($l, $d) => $d >= $first->toDateString() && $d <= $last->toDateString())->flatten(1);
        $approvedInMonth = $inMonth->where('status', 'approved');

        $onLeaveToday = LeaveApplicationDate::whereDate('leave_date', today())
            ->whereHas('application', fn ($q) => $scoped($q->where('status', 'approved')))
            ->with('application:id,emp_id')->get()->pluck('application.emp_id')->unique()->count();

        return [
            'first'  => $first,
            'weeks'  => collect(CarbonPeriod::create($gridStart, $gridEnd))->chunk(7)->map->values(),
            'byDay'  => $byDay,
            'prev'   => $first->copy()->subMonth()->format('Y-m'),
            'next'   => $first->copy()->addMonth()->format('Y-m'),
            'legend' => $inMonth->unique('code')->map(fn ($i) => ['code' => $i['code'], 'type' => $i['type']])->values(),
            'counts' => [
                'today' => $onLeaveToday,
                'out'   => $approvedInMonth->pluck('emp_id')->unique()->count(),
                'days'  => $approvedInMonth->count(),
            ],
        ];
    }
}