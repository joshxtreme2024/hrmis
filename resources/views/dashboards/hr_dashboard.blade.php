@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-6 bg-gradient-to-br from-slate-50 to-slate-100/80 min-h-screen">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Dashboard</h2>
                <p class="text-sm text-slate-500 mt-1 flex items-center gap-2">
                    <span>Welcome back,</span>
                    <span class="font-medium text-slate-700">{{ auth()->user()->personalData->first_name ?? 'Admin' }}</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span>
                    <span class="text-emerald-600 text-xs font-medium">Online</span>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-white/80 backdrop-blur-sm px-4 py-2 rounded-xl shadow-sm border border-slate-200/60 flex items-center gap-2">
                    <i class="bi bi-calendar3 text-slate-400"></i>
                    <span class="text-sm font-medium text-slate-600">{{ now()->format('l, F d, Y') }}</span>
                </div>
                <button onclick="window.location.reload()" 
                        class="bg-white hover:bg-slate-50 text-slate-600 px-4 py-2 rounded-xl shadow-sm border border-slate-200/60 transition-all duration-200 flex items-center gap-2 hover:shadow-md hover:border-slate-300">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span class="text-sm font-medium">Refresh</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 1. Top KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-lg transition-all duration-300 group hover:border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Employees</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1.5">{{ number_format($overview['total_employees'] ?? 0) }}</p>
                    <p class="text-xs text-emerald-600 mt-1 flex items-center">
                        <i class="bi bi-arrow-up-short"></i>
                        {{ $overview['growth'] ?? 0 }}% from last month
                    </p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-people text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-lg transition-all duration-300 group hover:border-emerald-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Active</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1.5">{{ number_format($overview['active_employees'] ?? 0) }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $overview['active_percentage'] ?? 0 }}% of total</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-person-check text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-lg transition-all duration-300 group hover:border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Present Today</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1.5">{{ number_format($overview['present_today'] ?? 0) }}</p>
                    <p class="text-xs text-slate-400 mt-1">Attendance rate: {{ $attendance['attendance_rate'] ?? 0 }}%</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-person-check-fill text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-lg transition-all duration-300 group hover:border-amber-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">On Leave</p>
                    <p class="text-2xl font-bold text-amber-500 mt-1.5">{{ number_format($overview['on_leave_today'] ?? 0) }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $overview['leave_percentage'] ?? 0 }}% of total</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-amber-500/20 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-user-clock text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-lg transition-all duration-300 group hover:border-rose-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Absent</p>
                    <p class="text-2xl font-bold text-rose-600 mt-1.5">{{ number_format($overview['absent_today'] ?? 0) }}</p>
                    <p class="text-xs text-slate-400 mt-1">Today's absence</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-rose-500/20 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-person-x text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-lg transition-all duration-300 group hover:border-orange-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Late</p>
                    <p class="text-2xl font-bold text-orange-500 mt-1.5">{{ number_format($overview['late_today'] ?? 0) }}</p>
                    <p class="text-xs text-slate-400 mt-1">Today's late arrivals</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-clock text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-lg transition-all duration-300 group hover:border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Pending</p>
                    <p class="text-2xl font-bold text-red-600 mt-1.5">{{ number_format($overview['pending_actions'] ?? 0) }}</p>
                    <p class="text-xs text-slate-400 mt-1">Requires attention</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-red-500/20 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-bell-fill text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Today's Attendance + Employee Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Today's Attendance -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>
                        Today's Attendance
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">{{ now()->format('F d, Y') }}</p>
                </div>
                <span class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full font-medium">
                    Rate: <span class="font-bold">{{ $attendance['attendance_rate'] ?? 0 }}%</span>
                </span>
            </div>

            <!-- Attendance Stats Grid -->
            <div class="grid grid-cols-3 gap-2.5 mb-5">
                <div class="text-center p-3 bg-emerald-50/80 rounded-xl border border-emerald-100/50">
                    <p class="text-xl font-bold text-emerald-600">{{ number_format($attendance['present'] ?? 0) }}</p>
                    <p class="text-[10px] font-medium text-emerald-500">Present</p>
                </div>
                <div class="text-center p-3 bg-amber-50/80 rounded-xl border border-amber-100/50">
                    <p class="text-xl font-bold text-amber-500">{{ number_format($attendance['late'] ?? 0) }}</p>
                    <p class="text-[10px] font-medium text-amber-500">Late</p>
                </div>
                <div class="text-center p-3 bg-blue-50/80 rounded-xl border border-blue-100/50">
                    <p class="text-xl font-bold text-blue-500">{{ number_format($attendance['on_official_business'] ?? 0) }}</p>
                    <p class="text-[10px] font-medium text-blue-500">Official Biz</p>
                </div>
                <div class="text-center p-3 bg-rose-50/80 rounded-xl border border-rose-100/50">
                    <p class="text-xl font-bold text-rose-600">{{ number_format($attendance['absent'] ?? 0) }}</p>
                    <p class="text-[10px] font-medium text-rose-500">Absent</p>
                </div>
                <div class="text-center p-3 bg-amber-100/60 rounded-xl border border-amber-200/50">
                    <p class="text-xl font-bold text-amber-700">{{ number_format($attendance['on_leave'] ?? 0) }}</p>
                    <p class="text-[10px] font-medium text-amber-600">On Leave</p>
                </div>
                <div class="text-center p-3 bg-slate-50/80 rounded-xl border border-slate-200/50">
                    <p class="text-xl font-bold text-slate-500">{{ number_format($attendance['no_record'] ?? 0) }}</p>
                    <p class="text-[10px] font-medium text-slate-400">No Record</p>
                </div>
            </div>

            <!-- Attendance Donut Chart -->
            <div class="h-28 flex items-center justify-center">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <!-- Employee Distribution - By Department -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 inline-block"></span>
                        Employees by Office
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Department distribution</p>
                </div>
                <span class="text-xs bg-slate-100 text-slate-500 px-3 py-1.5 rounded-full font-medium">Top 5</span>
            </div>
            <div class="space-y-3.5">
                @forelse(($distribution['by_department'] ?? []) as $name => $count)
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-600 truncate pr-2 font-medium">{{ $name }}</span>
                            <span class="text-slate-800 font-semibold">{{ $count }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 mt-1.5">
                            @php
                                $total = array_sum($distribution['by_department'] ?? []);
                                $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
                            @endphp
                            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2 rounded-full transition-all duration-1000" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <i class="bi bi-building text-3xl text-slate-300 block mb-2"></i>
                        <p class="text-sm text-slate-400">No department data</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Plantilla & Position Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-violet-500 inline-block"></span>
                        Plantilla Status
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Position status overview</p>
                </div>
                <span class="text-xs bg-violet-50 text-violet-600 px-3 py-1.5 rounded-full font-medium">LGU</span>
            </div>
            <div class="space-y-3.5">
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600 font-medium">Authorized Positions</span>
                        <span class="text-slate-800 font-bold">{{ $plantilla['total_positions'] ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 mt-1.5">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600 font-medium">Filled</span>
                        <span class="text-slate-800 font-bold">{{ $plantilla['filled'] ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 mt-1.5">
                        @php
                            $fillPercentage = ($plantilla['total_positions'] ?? 1) > 0 ? round((($plantilla['filled'] ?? 0) / ($plantilla['total_positions'] ?? 1)) * 100) : 0;
                        @endphp
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $fillPercentage }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600 font-medium">Vacant</span>
                        <span class="text-slate-800 font-bold">{{ $plantilla['vacant'] ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 mt-1.5">
                        @php
                            $vacancyPercentage = ($plantilla['total_positions'] ?? 1) > 0 ? round((($plantilla['vacant'] ?? 0) / ($plantilla['total_positions'] ?? 1)) * 100) : 0;
                        @endphp
                        <div class="bg-rose-500 h-2 rounded-full" style="width: {{ $vacancyPercentage }}%"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm pt-1 border-t border-slate-100">
                    <span class="text-slate-600 font-medium">Permanent</span>
                    <span class="text-slate-800 font-bold">{{ $plantilla['permanent'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-600 font-medium">Casual / JO / Contractual</span>
                    <span class="text-slate-800 font-bold">{{ $plantilla['casual_jo'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Leave Monitoring + 5. HR Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Leave Monitoring -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span>
                        Leave Monitoring
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Overview</p>
                </div>
                <span class="text-xs bg-amber-50 text-amber-600 px-3 py-1.5 rounded-full font-medium">Today: {{ $leaveMonitoring['on_leave_today'] ?? 0 }}</span>
            </div>
            <div class="grid grid-cols-3 gap-2.5 mb-5">
                <div class="text-center p-3 bg-amber-50/80 rounded-xl border border-amber-100/50">
                    <p class="text-xl font-bold text-amber-600">{{ number_format($leaveMonitoring['pending'] ?? 0) }}</p>
                    <p class="text-[10px] font-medium text-amber-500">Pending</p>
                </div>
                <div class="text-center p-3 bg-emerald-50/80 rounded-xl border border-emerald-100/50">
                    <p class="text-xl font-bold text-emerald-600">{{ number_format($leaveMonitoring['approved'] ?? 0) }}</p>
                    <p class="text-[10px] font-medium text-emerald-500">Approved</p>
                </div>
                <div class="text-center p-3 bg-rose-50/80 rounded-xl border border-rose-100/50">
                    <p class="text-xl font-bold text-rose-600">{{ number_format($leaveMonitoring['rejected'] ?? 0) }}</p>
                    <p class="text-[10px] font-medium text-rose-500">Rejected</p>
                </div>
            </div>
            
            <!-- Upcoming Leaves -->
            <div>
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2.5">Upcoming Leaves</p>
                <div class="space-y-2">
                    @forelse(($leaveMonitoring['upcoming'] ?? []) as $leave)
                        <div class="flex items-center justify-between text-sm p-2.5 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                            <span class="text-slate-700 font-medium">{{ $leave['employee'] }}</span>
                            <span class="text-slate-400 text-xs">{{ $leave['date'] }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-3">No upcoming leaves</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 5. HR Alerts -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 hover:shadow-lg transition-all duration-300 lg:col-span-2">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500 inline-block"></span>
                        HR Alerts
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Actions requiring your attention</p>
                </div>
                <span class="text-xs bg-rose-50 text-rose-600 px-3 py-1.5 rounded-full font-medium">{{ count($alerts ?? []) }} alerts</span>
            </div>
            <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1 custom-scrollbar">
                @forelse($alerts ?? [] as $alert)
                    <div class="flex items-start gap-3 p-3 rounded-xl border
                        @if($alert['type'] == 'danger') bg-rose-50/80 border-rose-200/60
                        @elseif($alert['type'] == 'warning') bg-amber-50/80 border-amber-200/60
                        @else bg-blue-50/80 border-blue-200/60 @endif">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center
                                @if($alert['type'] == 'danger') bg-rose-100 text-rose-600
                                @elseif($alert['type'] == 'warning') bg-amber-100 text-amber-600
                                @else bg-blue-100 text-blue-600 @endif">
                                <i class="bi {{ $alert['icon'] }} text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-700">{{ $alert['message'] }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center min-w-[24px] h-6 rounded-full text-xs font-bold px-2
                                @if($alert['type'] == 'danger') bg-rose-200 text-rose-700
                                @elseif($alert['type'] == 'warning') bg-amber-200 text-amber-700
                                @else bg-blue-200 text-blue-700 @endif">
                                {{ $alert['count'] }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-3">
                            <i class="bi bi-check-circle-fill text-2xl text-emerald-500"></i>
                        </div>
                        <p class="text-sm font-medium text-slate-600">All clear!</p>
                        <p class="text-xs text-slate-400">No pending alerts</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 6. Upcoming Events + 8. Personnel Movements -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Upcoming Events -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-purple-500 inline-block"></span>
                        Upcoming Events
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Birthdays, anniversaries, etc.</p>
                </div>
                <span class="text-xs bg-purple-50 text-purple-600 px-3 py-1.5 rounded-full font-medium">Next 30 days</span>
            </div>
            <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1 custom-scrollbar">
                @forelse($upcomingEvents ?? [] as $event)
                    <div class="flex items-center gap-3 p-2.5 hover:bg-slate-50 rounded-xl transition-colors border border-slate-100/80">
                        <div class="flex-shrink-0 text-2xl">{{ $event['icon'] }}</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-700">{{ $event['title'] }}</p>
                            <p class="text-xs text-slate-400">{{ $event['details'] }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">{{ $event['date'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <i class="bi bi-calendar text-3xl text-slate-300 block mb-2"></i>
                        <p class="text-sm text-slate-400">No upcoming events</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Personnel Movements -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-teal-500 inline-block"></span>
                        Recent Personnel Actions
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Last 30 days</p>
                </div>
                <a href="#" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium hover:underline">View all</a>
            </div>
            <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1 custom-scrollbar">
                @forelse($personnelMovements ?? [] as $movement)
                    <div class="flex items-center gap-3 p-2.5 hover:bg-slate-50 rounded-xl transition-colors border border-slate-100/80">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold
                            @if($movement['type'] == 'appointment') bg-emerald-500
                            @elseif($movement['type'] == 'promotion') bg-blue-500
                            @elseif($movement['type'] == 'separation') bg-rose-500
                            @else bg-slate-500 @endif">
                            <i class="bi 
                                @if($movement['type'] == 'appointment') bi-person-plus
                                @elseif($movement['type'] == 'promotion') bi-arrow-up
                                @elseif($movement['type'] == 'separation') bi-person-x
                                @else bi-arrow-right @endif text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-700">{{ $movement['employee'] }}</p>
                            <p class="text-xs text-slate-400">{{ $movement['action'] }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-xs text-slate-400">{{ $movement['date'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <i class="bi bi-arrow-left-right text-3xl text-slate-300 block mb-2"></i>
                        <p class="text-sm text-slate-400">No recent personnel actions</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-slate-400 inline-block"></span>
                    Quick Actions
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Common HR tasks</p>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
            @foreach($quickActions ?? [] as $action)
                <a href="{{ $action['route'] }}" 
                   class="flex flex-col items-center justify-center p-4 bg-slate-50/80 hover:bg-{{ $action['color'] }}-50/80 rounded-xl border border-slate-200/60 hover:border-{{ $action['color'] }}-300 transition-all duration-200 group">
                    <div class="w-11 h-11 rounded-xl bg-{{ $action['color'] }}-100 text-{{ $action['color'] }}-600 flex items-center justify-center mb-2 group-hover:bg-{{ $action['color'] }}-600 group-hover:text-white transition-all duration-200 shadow-sm group-hover:shadow-lg">
                        <i class="bi {{ $action['icon'] }} text-lg"></i>
                    </div>
                    <span class="text-xs font-medium text-slate-600 group-hover:text-{{ $action['color'] }}-700 text-center">{{ $action['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    .transition-all-duration-1000 {
        transition-duration: 1000ms;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Attendance Donut Chart
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Present', 'Late', 'Absent', 'On Leave'],
            datasets: [{
                data: [
                    {{ $attendance['present'] ?? 0 }},
                    {{ $attendance['late'] ?? 0 }},
                    {{ $attendance['absent'] ?? 0 }},
                    {{ $attendance['on_leave'] ?? 0 }}
                ],
                backgroundColor: [
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6'
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '72%',
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: 'white',
                    titleColor: '#1e293b',
                    bodyColor: '#475569',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 10,
                    displayColors: true,
                    boxPadding: 4,
                    usePointStyle: true,
                }
            }
        }
    });
});
</script>
@endpush