<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Departments;
use App\Models\Positions;
use App\Models\Leave;
use App\Models\Attendance;
use App\Models\PDSEmployment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Redirect based on role
        if ($user->hasRole('admin') || $user->hasRole('hr')) {
            return $this->hrDashboard();
        } else {
            return $this->employeeDashboard();
        }
    }

    /**
     * HR/Admin Dashboard
     */
    private function hrDashboard()
    {
        // 1. Employee Overview - Top KPI Cards
        $overview = $this->getEmployeeOverview();
        
        // 2. Today's Attendance
        $attendance = $this->getTodayAttendance();
        
        // 3. Employee Distribution
        $distribution = $this->getEmployeeDistribution();
        
        // 4. Leave Monitoring
        $leaveMonitoring = $this->getLeaveMonitoring();
        
        // 5. HR Alerts / Pending Actions
        $alerts = $this->getHrAlerts();
        
        // 6. Upcoming Events
        $upcomingEvents = $this->getUpcomingEvents();
        
        // 7. Plantilla & Position Status (LGU Specific)
        $plantilla = $this->getPlantillaStatus();
        
        // 8. Personnel Movements
        $personnelMovements = $this->getPersonnelMovements();
        
        // 9. Quick Actions
        $quickActions = $this->getQuickActions();
        
        return view('dashboards.hr_dashboard', compact(
            'overview',
            'attendance',
            'distribution',
            'leaveMonitoring',
            'alerts',
            'upcomingEvents',
            'plantilla',
            'personnelMovements',
            'quickActions'
        ));
    }

    /**
     * Employee Dashboard
     */
    private function employeeDashboard()
    {
        $user = auth()->user();
        
        // Employee Personal Info
        $personalInfo = $this->getEmployeePersonalInfo($user);
        
        // Employee Attendance Summary
        $attendanceSummary = $this->getEmployeeAttendanceSummary($user);
        
        // Employee Leave Summary
        $leaveSummary = $this->getEmployeeLeaveSummary($user);
        
        // Upcoming Events (Birthdays, Anniversaries)
        $upcomingEvents = $this->getUpcomingEvents();
        
        // Quick Actions for Employee
        $quickActions = $this->getEmployeeQuickActions();
        
        return view('dashboards.user_dashboard', compact(
            'user',
            'personalInfo',
            'attendanceSummary',
            'leaveSummary',
            'upcomingEvents',
            'quickActions'
        ));
    }

    // ==================== HR DASHBOARD METHODS ====================

    /**
     * 1. Employee Overview - Top KPI Cards
     */
    private function getEmployeeOverview()
    {
        $total = User::count();
        $active = User::whereHas('employment', function($q) {
            $q->where('status', 'active');
        })->count();
        $presentToday = 0; //Attendance::whereDate('date', now()->toDateString())
            // ->whereNotNull('check_in')
            // ->count();
        $onLeaveToday = 0; //Leave::whereDate('date_from', '<=', now())
            // ->whereDate('date_to', '>=', now())
            // ->where('status', 'approved')
            // ->count();
        $absentToday = 0; //max(0, $total - $presentToday - $onLeaveToday);
        $lateToday = 0; //Attendance::whereDate('date', now()->toDateString())
            // ->whereNotNull('check_in')
            // ->where('check_in', '>', now()->setTime(8, 0, 0)->toTimeString())
            // ->count();
        $departments = Departments::count();
        $pendingActions = 0; //Leave::where('status', 'pending')->count();

        return [
            'total_employees' => $total,
            'active_employees' => $active,
            'present_today' => $presentToday,
            'on_leave_today' => $onLeaveToday,
            'absent_today' => $absentToday,
            'late_today' => $lateToday,
            'departments' => $departments,
            'pending_actions' => $pendingActions,
        ];
    }

    /**
     * 2. Today's Attendance
     */
    private function getTodayAttendance()
    {
        $today = now()->toDateString();
        
        $present = 0; //Attendance::whereDate('date', $today)
            // ->whereNotNull('check_in')
            // ->count();
            
        $absent = 0; //User::whereDoesntHave('attendance', function($q) use ($today) {
        //     $q->whereDate('date', $today);
        // })->count();
        
        $onLeave = 0; //Leave::whereDate('date_from', '<=', now())
            // ->whereDate('date_to', '>=', now())
            // ->where('status', 'approved')
            // ->count();
            
        $late = 0; //Attendance::whereDate('date', $today)
            // ->whereNotNull('check_in')
            // ->where('check_in', '>', now()->setTime(8, 0, 0)->toTimeString())
            // ->count();
            
        $onOfficialBusiness = 0; //Attendance::whereDate('date', $today)
            // ->where('status', 'official_business')
            // ->count();
            
        $noRecord = 0; //User::whereDoesntHave('attendance', function($q) use ($today) {
        //     $q->whereDate('date', $today);
        // })->whereDoesntHave('leaves', function($q) use ($today) {
        //     $q->whereDate('date_from', '<=', now())
        //       ->whereDate('date_to', '>=', now())
        //       ->where('status', 'approved');
        // })->count();

        $total = User::count();
        $attendanceRate = 0; //$total > 0 ? round(($present / $total) * 100, 1) : 0;

        return [
            'present' => $present,
            'absent' => $absent,
            'on_leave' => $onLeave,
            'late' => $late,
            'on_official_business' => $onOfficialBusiness,
            'no_record' => $noRecord,
            'attendance_rate' => $attendanceRate,
            'total' => $total,
        ];
    }

    /**
     * 3. Employee Distribution
     */
    private function getEmployeeDistribution()
    {
        // By Department/Office
        $byDepartment = DB::table('departments')
            ->join('pds_employment_info', 'departments.id', '=', 'pds_employment_info.department_id')
            ->select('departments.name', DB::raw('COUNT(pds_employment_info.user_id) as count'))
            ->groupBy('departments.name')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->pluck('count', 'name')
            ->toArray();

        // By Employment Status
        $byStatus = User::whereHas('employment')
            ->with('employment')
            ->get()
            ->groupBy(function($user) {
                return $user->employment->status_label ?? 'N/A';
            })
            ->map(function($group) {
                return $group->count();
            })
            ->toArray();

        // By Employment Type
        $byEmploymentType = DB::table('pds_employment_info')
            ->select('employment_type', DB::raw('COUNT(*) as count'))
            ->groupBy('employment_type')
            ->pluck('count', 'employment_type')
            ->toArray();

        // By Gender
        $byGender = DB::table('users')
            ->join('pds_personal_data', 'users.id', '=', 'pds_personal_data.user_id')
            ->select('pds_personal_data.sex', DB::raw('COUNT(*) as count'))
            ->groupBy('pds_personal_data.sex')
            ->pluck('count', 'sex')
            ->toArray();

        return [
            'by_department' => $byDepartment,
            'by_status' => $byStatus,
            'by_employment_type' => $byEmploymentType,
            'by_gender' => $byGender,
        ];
    }

    /**
     * 4. Leave Monitoring
     */
    private function getLeaveMonitoring()
    {
        $pending = 0; //Leave::where('status', 'pending')->count();
        $approved = 0; //Leave::where('status', 'approved')->count();
        $rejected = 0; //Leave::where('status', 'rejected')->count();
        
        $onLeaveToday = 0; //Leave::whereDate('date_from', '<=', now())
            // ->whereDate('date_to', '>=', now())
            // ->where('status', 'approved')
            // ->count();
            
        // Upcoming leaves (next 7 days)
        $upcoming = []; //Leave::with('user')
            // ->whereDate('date_from', '>=', now())
            // ->whereDate('date_from', '<=', now()->addDays(7))
            // ->where('status', 'approved')
            // ->orderBy('date_from')
            // ->limit(5)
            // ->get()
            // ->map(function($leave) {
            //     return [
            //         'employee' => $leave->user->display_name ?? 'N/A',
            //         'date' => $leave->date_from->format('M d'),
            //         'type' => $leave->type,
            //     ];
            // })
            // ->toArray();

        return [
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'on_leave_today' => $onLeaveToday,
            'upcoming' => $upcoming,
        ];
    }

    /**
     * 5. HR Alerts
     */
    private function getHrAlerts()
    {
        $alerts = [];

        // Expiring contracts (within 30 days)
        $expiringContracts = 0; //User::whereHas('employment', function($q) {
        //     $q->whereDate('contract_end', '<=', now()->addDays(30))
        //       ->whereDate('contract_end', '>=', now());
        // })->count();
        
        if ($expiringContracts > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'bi-exclamation-triangle',
                'message' => "{$expiringContracts} employees have expiring contracts",
                'count' => $expiringContracts,
            ];
        }

        // Pending leave applications
        $pendingLeaves = 0; //Leave::where('status', 'pending')->count();
        if ($pendingLeaves > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'bi-clock',
                'message' => "{$pendingLeaves} leave applications awaiting approval",
                'count' => $pendingLeaves,
            ];
        }

        // Incomplete 201 files
        $incompleteFiles = User::whereDoesntHave('personalData')->count();
        if ($incompleteFiles > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'bi-file-earmark-excel',
                'message' => "{$incompleteFiles} employees have incomplete 201 files",
                'count' => $incompleteFiles,
            ];
        }

        // Missing attendance records (last 3 days)
        $missingAttendance = 0; //User::whereDoesntHave('attendance', function($q) {
        //     $q->whereDate('date', '>=', now()->subDays(3));
        // })->count();
        
        if ($missingAttendance > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'bi-calendar-x',
                'message' => "{$missingAttendance} employees have missing attendance records",
                'count' => $missingAttendance,
            ];
        }

        // Sort alerts by priority
        usort($alerts, function($a, $b) {
            $priority = ['danger' => 1, 'warning' => 2, 'info' => 3];
            return $priority[$a['type']] - $priority[$b['type']];
        });

        return $alerts;
    }

    /**
     * 6. Upcoming Events
     */
    private function getUpcomingEvents()
    {
        $events = [];
        $today = now()->startOfDay();
        $thirtyDaysFromNow = now()->startOfDay()->addDays(30);
        $user = auth()->user();
        $isHrOrAdmin = $user && ($user->hasRole('hr') || $user->hasRole('admin'));

        // Birthdays (next 30 days)
        $birthdaysQuery = User::with('personalData')
            ->whereHas('personalData', function($q) {
                $q->whereNotNull('birth_date');
            });

        // If employee, only get their own birthday
        if (!$isHrOrAdmin) {
            $birthdaysQuery->where('id', $user->id);
        }

        $birthdays = $birthdaysQuery->get()
            ->filter(function($user) use ($today, $thirtyDaysFromNow) {
                if (!$user->personalData || !$user->personalData->birth_date) {
                    return false;
                }
                $birthDate = $user->personalData->birth_date;
                $nextBirthday = $birthDate->copy()->setYear(now()->year);
                if ($nextBirthday->lt($today)) {
                    $nextBirthday->addYear();
                }
                return $nextBirthday->gte($today) && $nextBirthday->lte($thirtyDaysFromNow);
            })
            ->map(function($user) {
                $birthDate = $user->personalData->birth_date;
                $nextBirthday = $birthDate->copy()->setYear(now()->year);
                if ($nextBirthday->lt(now()->startOfDay())) {
                    $nextBirthday->addYear();
                }
                return [
                    'type' => 'birthday',
                    'icon' => '🎂',
                    'title' => "Birthday: {$user->display_name}",
                    'date' => $nextBirthday->format('M d'),
                    'details' => "Turning " . ($birthDate->age + 1),
                    'employee' => $user->display_name,
                    'sort_date' => $nextBirthday->timestamp,
                ];
            });

        $events = array_merge($events, $birthdays->toArray());

        // Work Anniversaries (next 30 days)
        $anniversariesQuery = User::whereHas('employment', function($q) {
            $q->whereNotNull('hired_at');
        })->with('employment');

        // If employee, only get their own anniversary
        if (!$isHrOrAdmin) {
            $anniversariesQuery->where('id', $user->id);
        }

        $anniversaries = $anniversariesQuery->get()
            ->filter(function($user) use ($today, $thirtyDaysFromNow) {
                if (!$user->employment || !$user->employment->hired_at) {
                    return false;
                }
                $hireDate = $user->employment->hired_at;
                $nextAnniversary = $hireDate->copy()->setYear(now()->year);
                if ($nextAnniversary->lt($today)) {
                    $nextAnniversary->addYear();
                }
                return $nextAnniversary->gte($today) && $nextAnniversary->lte($thirtyDaysFromNow);
            })
            ->map(function($user) {
                $hireDate = $user->employment->hired_at;
                $nextAnniversary = $hireDate->copy()->setYear(now()->year);
                if ($nextAnniversary->lt(now()->startOfDay())) {
                    $nextAnniversary->addYear();
                }
                return [
                    'type' => 'anniversary',
                    'icon' => '📅',
                    'title' => "Work Anniversary: {$user->display_name}",
                    'date' => $nextAnniversary->format('M d'),
                    'details' => ($hireDate->diffInYears(now()) + 1) . " years",
                    'employee' => $user->display_name,
                    'sort_date' => $nextAnniversary->timestamp,
                ];
            });

        $events = array_merge($events, $anniversaries->toArray());

        // Sort by date
        usort($events, function($a, $b) {
            return $a['sort_date'] - $b['sort_date'];
        });

        // Remove sort_date and limit to 10
        $events = array_map(function($event) {
            unset($event['sort_date']);
            return $event;
        }, $events);

        return array_slice($events, 0, 10);
    }

    /**
     * 7. Plantilla Status
     */
    private function getPlantillaStatus()
    {
        $totalPositions = Positions::count();
        $filledPositions = User::whereHas('employment')->count();
        $vacantPositions = max(0, $totalPositions - $filledPositions);
        
        $casualJO = PDSEmployment::whereIn('employment_type', ['casual', 'job_order', 'contractual'])->count();
        $permanent = PDSEmployment::where('employment_type', 'permanent')->count();

        return [
            'total_positions' => $totalPositions,
            'filled' => $filledPositions,
            'vacant' => $vacantPositions,
            'permanent' => $permanent,
            'casual_jo' => $casualJO,
            'vacancy_rate' => $totalPositions > 0 ? round(($vacantPositions / $totalPositions) * 100, 1) : 0,
        ];
    }

    /**
     * 8. Personnel Movements
     */
    private function getPersonnelMovements()
    {
        $movements = [];

        // Recent hires
        $newHires = User::with('personalData')
            ->whereHas('employment', function($q) {
                $q->whereDate('hired_at', '>=', now()->subDays(30));
            })
            ->latest()
            ->take(3)
            ->get()
            ->map(function($user) {
                return [
                    'employee' => $user->display_name,
                    'action' => 'Appointment',
                    'date' => $user->employment->hired_at->format('M d'),
                    'type' => 'appointment',
                ];
            });

        $movements = array_merge($movements, $newHires->toArray());

        // Recent resignations/terminations
        $resignations = User::with('personalData')
            ->whereHas('employment', function($q) {
                $q->whereIn('status', ['resigned', 'terminated'])
                  ->whereDate('updated_at', '>=', now()->subDays(30));
            })
            ->latest('updated_at')
            ->take(2)
            ->get()
            ->map(function($user) {
                return [
                    'employee' => $user->display_name,
                    'action' => $user->employment->status_label,
                    'date' => $user->updated_at->format('M d'),
                    'type' => 'separation',
                ];
            });

        $movements = array_merge($movements, $resignations->toArray());

        // Sort by date (most recent first)
        usort($movements, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return array_slice($movements, 0, 10);
    }

    /**
     * 9. Quick Actions for HR
     */
    private function getQuickActions()
    {
        return [
            [
                'icon' => 'bi-file-text',
                'label' => 'File Leave',
                'route' => route('hr.leaves.create'),
                'color' => 'green',
            ],
            [
                'icon' => 'bi-calendar-check',
                'label' => 'Attendance',
                'route' => route('hr.attendance.index'),
                'color' => 'yellow',
            ],
            [
                'icon' => 'bi-file-earmark-text',
                'label' => 'Reports',
                'route' => route('hr.reports.index'),
                'color' => 'purple',
            ],
            [
                'icon' => 'bi-check-circle',
                'label' => 'Process Leave',
                'route' => route('hr.leaves.pending'),
                'color' => 'indigo',
            ],
            [
                'icon' => 'bi-search',
                'label' => 'Search',
                'route' => route('hr.employees.search'),
                'color' => 'gray',
            ],
        ];
    }

    // ==================== EMPLOYEE DASHBOARD METHODS ====================

    /**
     * Get Employee Personal Information
     */
    private function getEmployeePersonalInfo($user)
    {
        return [
            'name' => $user->display_name,
            'email' => $user->email,
            'employee_id' => $user->employment?->employee_id ?? 'N/A',
            'department' => $user->employment?->department?->name ?? 'N/A',
            'position' => $user->employment?->position?->title ?? 'N/A',
            'hire_date' => $user->employment?->formatted_hire_date ?? 'N/A',
            'status' => $user->employment?->status_label ?? 'N/A',
            'profile_picture' => $user->profile_picture,
            'initials' => $user->initials,
        ];
    }

    /**
     * Get Employee Attendance Summary
     */
    private function getEmployeeAttendanceSummary($user)
    {
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        
        $todayAttendance = 0; //Attendance::where('user_id', $user->id)
            // ->whereDate('date', $today)
            // ->first();

        $monthlyStats = NULL; //Attendance::where('user_id', $user->id)
            // ->whereBetween('date', [$monthStart, $today])
            // ->select(
            //     DB::raw('COUNT(*) as total_days'),
            //     DB::raw('SUM(CASE WHEN check_in IS NOT NULL THEN 1 ELSE 0 END) as present_days'),
            //     DB::raw('SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_days'),
            //     DB::raw('SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_days'),
            //     DB::raw('SUM(CASE WHEN status = "leave" THEN 1 ELSE 0 END) as leave_days')
            // )
            // ->first();

        return [
            'today' => [
                'status' => $todayAttendance ? ($todayAttendance->check_in ? 'Present' : 'Absent') : 'Not Recorded',
                'check_in' => 'N/A', //$todayAttendance?->check_in?->format('h:i A') ?? 'N/A',
                'check_out' => 'N/A', //$todayAttendance?->check_out?->format('h:i A') ?? 'N/A',
            ],
            'monthly' => [
                'total_days' => $monthlyStats?->total_days ?? 0,
                'present' => $monthlyStats?->present_days ?? 0,
                'late' => $monthlyStats?->late_days ?? 0,
                'absent' => $monthlyStats?->absent_days ?? 0,
                'leave' => $monthlyStats?->leave_days ?? 0,
                'attendance_rate' => ($monthlyStats?->total_days ?? 1) > 0 
                    ? round((($monthlyStats?->present_days ?? 0) / ($monthlyStats?->total_days ?? 1)) * 100, 1) 
                    : 0,
            ],
        ];
    }

    /**
     * Get Employee Leave Summary
     */
    private function getEmployeeLeaveSummary($user)
    {
        $pending = 0; //Leave::where('user_id', $user->id)
            // ->where('status', 'pending')
            // ->count();
            
        $approved = 0; //Leave::where('user_id', $user->id)
            // ->where('status', 'approved')
            // ->count();
            
        $rejected = 0; //Leave::where('user_id', $user->id)
            // ->where('status', 'rejected')
            // ->count();
            
        $upcoming = []; //Leave::where('user_id', $user->id)
            // ->where('status', 'approved')
            // ->whereDate('date_from', '>=', now())
            // ->orderBy('date_from')
            // ->limit(5)
            // ->get()
            // ->map(function($leave) {
            //     return [
            //         'type' => $leave->type,
            //         'date_from' => $leave->date_from->format('M d'),
            //         'date_to' => $leave->date_to->format('M d'),
            //         'status' => $leave->status,
            //     ];
            // });

        $leaveBalance = [
            'vacation' => 0, //15 - Leave::where('user_id', $user->id)
                // ->where('type', 'vacation')
                // ->where('status', 'approved')
                // ->sum(DB::raw('DATEDIFF(date_to, date_from) + 1')),
            'sick' => 0, //15 - Leave::where('user_id', $user->id)
                // ->where('type', 'sick')
                // ->where('status', 'approved')
                // ->sum(DB::raw('DATEDIFF(date_to, date_from) + 1')),
            'emergency' => 0, //5 - Leave::where('user_id', $user->id)
                // ->where('type', 'emergency')
                // ->where('status', 'approved')
                // ->sum(DB::raw('DATEDIFF(date_to, date_from) + 1')),
        ];

        return [
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'upcoming' => $upcoming,
            'balance' => $leaveBalance,
        ];
    }

    /**
     * Quick Actions for Employee
     */
    private function getEmployeeQuickActions()
    {
        return [
            [
                'icon' => 'bi-file-text',
                'label' => 'File Leave',
                'route' => route('employee.leaves.create'),
                'color' => 'blue',
            ],
            [
                'icon' => 'bi-calendar-check',
                'label' => 'My Attendance',
                'route' => route('employee.attendance.index'),
                'color' => 'green',
            ],
            [
                'icon' => 'bi-clock-history',
                'label' => 'Leave History',
                'route' => route('employee.leaves.history'),
                'color' => 'yellow',
            ],
            [
                'icon' => 'bi-person-badge',
                'label' => 'My Profile',
                'route' => route('employee.profile.show'),
                'color' => 'purple',
            ],
            [
                'icon' => 'bi-file-earmark-text',
                'label' => 'PDS',
                'route' => route('employee.pds.show'),
                'color' => 'indigo',
            ],
        ];
    }
}