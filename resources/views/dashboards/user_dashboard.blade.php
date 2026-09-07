{{-- resources/views/employee/dashboard.blade.php --}}
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
                    <span class="font-medium text-slate-700">{{ $personalInfo['name'] ?? 'Employee' }}</span>
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

    <!-- Employee Profile Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 mb-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center">
            <div class="flex-shrink-0 relative">
                @if($personalInfo['profile_picture'])
                    <img src="{{ asset('storage/' . $personalInfo['profile_picture']) }}" 
                         alt="{{ $personalInfo['name'] }}" 
                         class="h-20 w-20 rounded-full object-cover border-4 border-white shadow-lg">
                @else
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center text-2xl font-bold shadow-lg">
                        {{ $personalInfo['initials'] ?? '?' }}
                    </div>
                @endif
                <div class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white"></div>
            </div>
            <div class="ml-5 flex-1">
                <h3 class="text-xl font-bold text-slate-800">{{ $personalInfo['name'] }}</h3>
                <div class="flex flex-wrap items-center gap-3 mt-1">
                    <span class="text-sm text-slate-600 flex items-center">
                        <i class="bi bi-building mr-1.5 text-slate-400"></i>
                        {{ $personalInfo['department'] }}
                    </span>
                    <span class="text-slate-300">|</span>
                    <span class="text-sm text-slate-600 flex items-center">
                        <i class="bi bi-briefcase mr-1.5 text-slate-400"></i>
                        {{ $personalInfo['position'] }}
                    </span>
                    <span class="text-slate-300">|</span>
                    <span class="text-sm text-slate-600 flex items-center">
                        <i class="bi bi-person-badge mr-1.5 text-slate-400"></i>
                        ID: {{ $personalInfo['employee_id'] }}
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100/60">
                        <i class="bi bi-calendar-check mr-1.5"></i>
                        Hired: {{ $personalInfo['hire_date'] }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium 
                        @if($personalInfo['status'] == 'Active') bg-emerald-50 text-emerald-700 border border-emerald-100/60
                        @elseif($personalInfo['status'] == 'On Leave') bg-amber-50 text-amber-700 border border-amber-100/60
                        @else bg-slate-50 text-slate-700 border border-slate-200/60 @endif">
                        <span class="w-1.5 h-1.5 rounded-full inline-block mr-1.5 
                            @if($personalInfo['status'] == 'Active') bg-emerald-500
                            @elseif($personalInfo['status'] == 'On Leave') bg-amber-500
                            @else bg-slate-400 @endif"></span>
                        Status: {{ $personalInfo['status'] }}
                    </span>
                </div>
            </div>
            <div class="flex gap-1.5">
                <a href="{{ route('employee.profile.edit') }}" 
                   class="p-2.5 text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200 hover:shadow-sm group">
                    <i class="bi bi-pencil group-hover:scale-110 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Today's Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-lg transition-all duration-300 group hover:border-emerald-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Today's Status</p>
                    <p class="text-xl font-bold mt-1.5 
                        @if($attendanceSummary['today']['status'] == 'Present') text-emerald-600
                        @elseif($attendanceSummary['today']['status'] == 'Absent') text-rose-600
                        @else text-slate-600 @endif">
                        {{ $attendanceSummary['today']['status'] }}
                    </p>
                    <p class="text-xs text-slate-400">Check-in: {{ $attendanceSummary['today']['check_in'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl flex items-center justify-center
                    @if($attendanceSummary['today']['status'] == 'Present') bg-emerald-50 text-emerald-600
                    @elseif($attendanceSummary['today']['status'] == 'Absent') bg-rose-50 text-rose-600
                    @else bg-slate-50 text-slate-600 @endif 
                    group-hover:scale-110 transition-transform duration-300">
                    <i class="bi 
                        @if($attendanceSummary['today']['status'] == 'Present') bi-check-circle-fill text-xl
                        @elseif($attendanceSummary['today']['status'] == 'Absent') bi-x-circle-fill text-xl
                        @else bi-clock text-xl @endif"></i>
                </div>
            </div>
        </div>

        <!-- Attendance Rate -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-lg transition-all duration-300 group hover:border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Attendance Rate</p>
                    <p class="text-xl font-bold text-blue-600 mt-1.5">{{ $attendanceSummary['monthly']['attendance_rate'] ?? 0 }}%</p>
                    <p class="text-xs text-slate-400">This month</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-percent text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Leave Balance -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-lg transition-all duration-300 group hover:border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Leave Balance</p>
                    <p class="text-xl font-bold text-purple-600 mt-1.5">{{ $leaveSummary['balance']['vacation'] ?? 0 }}</p>
                    <p class="text-xs text-slate-400">Vacation days left</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-calendar2-week text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Leaves -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 hover:shadow-lg transition-all duration-300 group hover:border-amber-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Pending Leaves</p>
                    <p class="text-xl font-bold text-amber-500 mt-1.5">{{ $leaveSummary['pending'] ?? 0 }}</p>
                    <p class="text-xs text-slate-400">Awaiting approval</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-amber-500/20 group-hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-clock text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Summary & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Leave Summary -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 hover:shadow-lg transition-all duration-300 lg:col-span-2">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span>
                        My Leave Summary
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Overview of your leaves</p>
                </div>
                <a href="{{ route('employee.leaves.history') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium hover:underline">View all</a>
            </div>
            
            <!-- Leave Stats -->
            <div class="grid grid-cols-3 gap-3 mb-5">
                <div class="text-center p-3 bg-amber-50/80 rounded-xl border border-amber-100/50">
                    <p class="text-xl font-bold text-amber-600">{{ $leaveSummary['pending'] ?? 0 }}</p>
                    <p class="text-[10px] font-medium text-amber-500">Pending</p>
                </div>
                <div class="text-center p-3 bg-emerald-50/80 rounded-xl border border-emerald-100/50">
                    <p class="text-xl font-bold text-emerald-600">{{ $leaveSummary['approved'] ?? 0 }}</p>
                    <p class="text-[10px] font-medium text-emerald-500">Approved</p>
                </div>
                <div class="text-center p-3 bg-rose-50/80 rounded-xl border border-rose-100/50">
                    <p class="text-xl font-bold text-rose-600">{{ $leaveSummary['rejected'] ?? 0 }}</p>
                    <p class="text-[10px] font-medium text-rose-500">Rejected</p>
                </div>
            </div>

            <!-- Leave Balance Details -->
            <div>
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2.5">Leave Balance</p>
                <div class="grid grid-cols-3 gap-3">
                    <div class="flex items-center justify-between p-2.5 bg-slate-50/80 rounded-xl border border-slate-200/50">
                        <span class="text-sm text-slate-600">Vacation</span>
                        <span class="text-sm font-bold text-slate-800">{{ $leaveSummary['balance']['vacation'] ?? 0 }} days</span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 bg-slate-50/80 rounded-xl border border-slate-200/50">
                        <span class="text-sm text-slate-600">Sick</span>
                        <span class="text-sm font-bold text-slate-800">{{ $leaveSummary['balance']['sick'] ?? 0 }} days</span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 bg-slate-50/80 rounded-xl border border-slate-200/50">
                        <span class="text-sm text-slate-600">Emergency</span>
                        <span class="text-sm font-bold text-slate-800">{{ $leaveSummary['balance']['emergency'] ?? 0 }} days</span>
                    </div>
                </div>
            </div>

            <!-- Upcoming Leaves -->
            @if(count($leaveSummary['upcoming'] ?? []) > 0)
                <div class="mt-4">
                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2.5">Upcoming Leaves</p>
                    <div class="space-y-2">
                        @foreach($leaveSummary['upcoming'] as $leave)
                            <div class="flex items-center justify-between p-2.5 bg-slate-50/80 rounded-xl border border-slate-200/50">
                                <div>
                                    <p class="text-sm font-medium text-slate-700">{{ ucfirst($leave['type']) }} Leave</p>
                                    <p class="text-xs text-slate-400">{{ $leave['date_from'] }} - {{ $leave['date_to'] }}</p>
                                </div>
                                <span class="text-xs bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full font-medium">Approved</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-slate-400 inline-block"></span>
                        Quick Actions
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Common tasks</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
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

    <!-- Upcoming Events -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-500 inline-block"></span>
                    Upcoming Events
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Your birthdays and anniversaries</p>
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
                <div class="text-center py-8">
                    <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3">
                        <i class="bi bi-calendar text-2xl text-slate-300"></i>
                    </div>
                    <p class="text-sm font-medium text-slate-600">No upcoming events</p>
                    <p class="text-xs text-slate-400">You don't have any events in the next 30 days</p>
                </div>
            @endforelse
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
</style>
@endpush