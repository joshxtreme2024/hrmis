@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
            <p class="text-sm text-gray-500 mt-1">Welcome back, {{ auth()->user()->name ?? 'Admin' }}! 👋</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">
                <i class="bi bi-calendar3 mr-1"></i>
                {{ now()->format('l, F d, Y') }}
            </span>
            <button onclick="window.location.reload()" class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg shadow-sm border border-gray-200 transition-all duration-200 flex items-center gap-2">
                <i class="bi bi-arrow-clockwise"></i>
                <span class="text-sm">Refresh</span>
            </button>
        </div>
    </div>

    <!-- 1. Top KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Employees</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($overview['total_employees'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="bi bi-people text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Active</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($overview['active_employees'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                    <i class="bi bi-person-check text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Present Today</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($overview['present_today'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="bi bi-person-check text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">On Leave</p>
                    <p class="text-2xl font-bold text-amber-500 mt-1">{{ number_format($overview['on_leave_today'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center text-amber-500">
                    <i class="bi bi-person-clock text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Absent</p>
                    <p class="text-2xl font-bold text-rose-600 mt-1">{{ number_format($overview['absent_today'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-rose-50 rounded-lg flex items-center justify-center text-rose-600">
                    <i class="bi bi-person-x text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Late</p>
                    <p class="text-2xl font-bold text-orange-500 mt-1">{{ number_format($overview['late_today'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500">
                    <i class="bi bi-clock text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pending</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($overview['pending_actions'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center text-red-600">
                    <i class="bi bi-bell text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Today's Attendance + Employee Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Today's Attendance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">Today's Attendance</h3>
                    <p class="text-xs text-gray-400">{{ now()->format('F d, Y') }}</p>
                </div>
                <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">Attendance Rate: <span class="font-bold text-blue-600">{{ $attendance['attendance_rate'] ?? 0 }}%</span></span>
            </div>

            <!-- Attendance Stats Grid -->
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="text-center p-3 bg-emerald-50 rounded-lg">
                    <p class="text-xl font-bold text-emerald-600">{{ number_format($attendance['present'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Present</p>
                </div>
                <div class="text-center p-3 bg-amber-50 rounded-lg">
                    <p class="text-xl font-bold text-amber-500">{{ number_format($attendance['late'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Late</p>
                </div>
                <div class="text-center p-3 bg-blue-50 rounded-lg">
                    <p class="text-xl font-bold text-blue-500">{{ number_format($attendance['on_official_business'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Official Biz</p>
                </div>
                <div class="text-center p-3 bg-rose-50 rounded-lg">
                    <p class="text-xl font-bold text-rose-600">{{ number_format($attendance['absent'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Absent</p>
                </div>
                <div class="text-center p-3 bg-amber-100 rounded-lg">
                    <p class="text-xl font-bold text-amber-700">{{ number_format($attendance['on_leave'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">On Leave</p>
                </div>
                <div class="text-center p-3 bg-gray-100 rounded-lg">
                    <p class="text-xl font-bold text-gray-600">{{ number_format($attendance['no_record'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">No Record</p>
                </div>
            </div>

            <!-- Attendance Donut Chart -->
            <div class="h-32 flex items-center justify-center">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <!-- Employee Distribution - By Department -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">Employees by Office</h3>
                    <p class="text-xs text-gray-400">Department distribution</p>
                </div>
                <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">Top 5</span>
            </div>
            <div class="space-y-3">
                @forelse(($distribution['by_department'] ?? []) as $name => $count)
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 truncate pr-2">{{ $name }}</span>
                            <span class="text-gray-800 font-semibold">{{ $count }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 mt-1">
                            @php
                                $total = array_sum($distribution['by_department'] ?? []);
                                $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
                            @endphp
                            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2 rounded-full transition-all duration-1000" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-building text-2xl text-gray-300 block mb-1"></i>
                        <p class="text-sm text-gray-400">No department data</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Plantilla & Position Status (LGU) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">Plantilla Status</h3>
                    <p class="text-xs text-gray-400">Position status overview</p>
                </div>
                <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">LGU</span>
            </div>
            <div class="space-y-3">
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Authorized Positions</span>
                        <span class="text-gray-800 font-semibold">{{ $plantilla['total_positions'] ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 mt-1">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Filled</span>
                        <span class="text-gray-800 font-semibold">{{ $plantilla['filled'] ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 mt-1">
                        @php
                            $fillPercentage = ($plantilla['total_positions'] ?? 1) > 0 ? round((($plantilla['filled'] ?? 0) / ($plantilla['total_positions'] ?? 1)) * 100) : 0;
                        @endphp
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $fillPercentage }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Vacant</span>
                        <span class="text-gray-800 font-semibold">{{ $plantilla['vacant'] ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 mt-1">
                        @php
                            $vacancyPercentage = ($plantilla['total_positions'] ?? 1) > 0 ? round((($plantilla['vacant'] ?? 0) / ($plantilla['total_positions'] ?? 1)) * 100) : 0;
                        @endphp
                        <div class="bg-rose-500 h-2 rounded-full" style="width: {{ $vacancyPercentage }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Permanent</span>
                        <span class="text-gray-800 font-semibold">{{ $plantilla['permanent'] ?? 0 }}</span>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Casual / JO / Contractual</span>
                        <span class="text-gray-800 font-semibold">{{ $plantilla['casual_jo'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Leave Monitoring + 5. HR Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Leave Monitoring -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">Leave Monitoring</h3>
                    <p class="text-xs text-gray-400">Overview</p>
                </div>
                <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">Today: {{ $leaveMonitoring['on_leave_today'] ?? 0 }}</span>
            </div>
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="text-center p-3 bg-amber-50 rounded-lg">
                    <p class="text-xl font-bold text-amber-600">{{ number_format($leaveMonitoring['pending'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Pending</p>
                </div>
                <div class="text-center p-3 bg-emerald-50 rounded-lg">
                    <p class="text-xl font-bold text-emerald-600">{{ number_format($leaveMonitoring['approved'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Approved</p>
                </div>
                <div class="text-center p-3 bg-rose-50 rounded-lg">
                    <p class="text-xl font-bold text-rose-600">{{ number_format($leaveMonitoring['rejected'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Rejected</p>
                </div>
            </div>
            
            <!-- Upcoming Leaves -->
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Upcoming Leaves</p>
                <div class="space-y-2">
                    @forelse(($leaveMonitoring['upcoming'] ?? []) as $leave)
                        <div class="flex items-center justify-between text-sm p-2 hover:bg-gray-50 rounded-lg">
                            <span class="text-gray-700">{{ $leave['employee'] }}</span>
                            <span class="text-gray-500 text-xs">{{ $leave['date'] }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-2">No upcoming leaves</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 5. HR Alerts -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">HR Alerts</h3>
                    <p class="text-xs text-gray-400">Actions requiring your attention</p>
                </div>
                <span class="text-xs bg-red-50 text-red-600 px-2.5 py-1 rounded-full">{{ count($alerts ?? []) }} alerts</span>
            </div>
            <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                @forelse($alerts ?? [] as $alert)
                    <div class="flex items-start gap-3 p-3 rounded-lg border
                        @if($alert['type'] == 'danger') bg-red-50 border-red-200
                        @elseif($alert['type'] == 'warning') bg-amber-50 border-amber-200
                        @else bg-blue-50 border-blue-200 @endif">
                        <div class="flex-shrink-0 mt-0.5">
                            <i class="bi {{ $alert['icon'] }} 
                                @if($alert['type'] == 'danger') text-red-500
                                @elseif($alert['type'] == 'warning') text-amber-500
                                @else text-blue-500 @endif text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">{{ $alert['message'] }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold
                                @if($alert['type'] == 'danger') bg-red-200 text-red-700
                                @elseif($alert['type'] == 'warning') bg-amber-200 text-amber-700
                                @else bg-blue-200 text-blue-700 @endif">
                                {{ $alert['count'] }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <i class="bi bi-check-circle-fill text-3xl text-emerald-500 block mb-2"></i>
                        <p class="text-sm text-gray-400">All clear! No pending alerts.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 6. Upcoming Events + 8. Personnel Movements -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Upcoming Events -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">Upcoming Events</h3>
                    <p class="text-xs text-gray-400">Birthdays, anniversaries, etc.</p>
                </div>
                <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">Next 30 days</span>
            </div>
            <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                @forelse($upcomingEvents ?? [] as $event)
                    <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors border border-gray-50">
                        <div class="flex-shrink-0 text-2xl">{{ $event['icon'] }}</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-800">{{ $event['title'] }}</p>
                            <p class="text-xs text-gray-400">{{ $event['details'] }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">{{ $event['date'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <i class="bi bi-calendar text-3xl text-gray-300 block mb-2"></i>
                        <p class="text-sm text-gray-400">No upcoming events</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Personnel Movements -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">Recent Personnel Actions</h3>
                    <p class="text-xs text-gray-400">Last 30 days</p>
                </div>
                <a href="#" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium hover:underline">View all</a>
            </div>
            <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                @forelse($personnelMovements ?? [] as $movement)
                    <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors border border-gray-50">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold
                            @if($movement['type'] == 'appointment') bg-emerald-500
                            @elseif($movement['type'] == 'promotion') bg-blue-500
                            @elseif($movement['type'] == 'separation') bg-rose-500
                            @else bg-gray-500 @endif">
                            <i class="bi 
                                @if($movement['type'] == 'appointment') bi-person-plus
                                @elseif($movement['type'] == 'promotion') bi-arrow-up
                                @elseif($movement['type'] == 'separation') bi-person-x
                                @else bi-arrow-right @endif"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-800">{{ $movement['employee'] }}</p>
                            <p class="text-xs text-gray-400">{{ $movement['action'] }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-xs text-gray-500">{{ $movement['date'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <i class="bi bi-arrow-left-right text-3xl text-gray-300 block mb-2"></i>
                        <p class="text-sm text-gray-400">No recent personnel actions</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-700">Quick Actions</h3>
                <p class="text-xs text-gray-400">Common HR tasks</p>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
            @foreach($quickActions ?? [] as $action)
                <a href="{{ $action['route'] }}" 
                   class="flex flex-col items-center justify-center p-4 bg-gray-50 hover:bg-{{ $action['color'] }}-50 rounded-xl border border-gray-100 hover:border-{{ $action['color'] }}-300 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-full bg-{{ $action['color'] }}-100 text-{{ $action['color'] }}-600 flex items-center justify-center mb-2 group-hover:bg-{{ $action['color'] }}-600 group-hover:text-white transition-all duration-200">
                        <i class="bi {{ $action['icon'] }} text-lg"></i>
                    </div>
                    <span class="text-xs text-gray-600 group-hover:text-{{ $action['color'] }}-700 font-medium text-center">{{ $action['label'] }}</span>
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
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
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
            cutout: '70%',
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: 'white',
                    titleColor: '#1f2937',
                    bodyColor: '#4b5563',
                    borderColor: '#e5e7eb',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 10,
                }
            }
        }
    });
});
</script>
@endpush