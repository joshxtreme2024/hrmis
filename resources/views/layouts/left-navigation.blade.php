@props(['collapsible' => true])

@php
$menuItems = [
    [
        'title' => 'Dashboard',
        'icon' => 'bi-speedometer2',
        'route' => 'dashboard',
        'permission' => null,
        'badge' => null,
        'color' => 'from-blue-500 to-blue-600'
    ],
    [
        'title' => 'Employee Management',
        'icon' => 'bi-people',
        'permission' => 'view-employees',
        'badge' => '12',
        'color' => 'from-emerald-500 to-teal-600',
        'submenu' => [
            ['title' => 'All Employees', 'route' => 'employees.index', 'icon' => 'bi-person-badge'],
            ['title' => 'Departments', 'route' => 'departments.index', 'icon' => 'bi-building'],
            ['title' => 'Positions', 'route' => 'positions.index', 'icon' => 'bi-briefcase'],
            ['title' => 'Employment Types', 'route' => 'employment-types.index', 'icon' => 'bi-tags'],
        ]
    ],
    [
        'title' => 'Time & Attendance',
        'icon' => 'bi-clock-history',
        'permission' => 'view-attendance',
        'badge' => '3 pending',
        'color' => 'from-orange-500 to-amber-600',
        'submenu' => [
            ['title' => 'Daily Attendance', 'route' => 'attendance.daily', 'icon' => 'bi-calendar-day'],
            ['title' => 'Timesheets', 'route' => 'attendance.timesheets', 'icon' => 'bi-file-text'],
            ['title' => 'Shifts', 'route' => 'shifts.index', 'icon' => 'bi-arrow-repeat'],
            ['title' => 'Overtime', 'route' => 'overtime.index', 'icon' => 'bi-clock'],
        ]
    ],
    [
        'title' => 'Leave Management',
        'icon' => 'bi-calendar-check',
        'permission' => 'view-leave',
        'badge' => '8 requests',
        'color' => 'from-purple-500 to-pink-600',
        'submenu' => [
            ['title' => 'Leave Requests', 'route' => 'leave.requests', 'icon' => 'bi-inbox'],
            ['title' => 'Leave Calendar', 'route' => 'leave.calendar', 'icon' => 'bi-calendar3'],
            ['title' => 'Leave Types', 'route' => 'leave.types', 'icon' => 'bi-tag'],
            ['title' => 'My Leaves', 'route' => 'leave.my-requests', 'icon' => 'bi-person-check'],
        ]
    ],
    [
        'title' => 'Payroll',
        'icon' => 'bi-cash-stack',
        'permission' => 'view-payroll',
        'badge' => null,
        'color' => 'from-green-500 to-emerald-600',
        'submenu' => [
            ['title' => 'Salary Management', 'route' => 'payroll.salaries', 'icon' => 'bi-cash'],
            ['title' => 'Payslips', 'route' => 'payroll.payslips', 'icon' => 'bi-file-earmark-text'],
            ['title' => 'Tax Information', 'route' => 'payroll.tax', 'icon' => 'bi-percent'],
            ['title' => 'Benefits', 'route' => 'payroll.benefits', 'icon' => 'bi-gift'],
        ]
    ],
    [
        'title' => 'Recruitment',
        'icon' => 'bi-person-plus',
        'permission' => 'view-recruitment',
        'badge' => '5 new',
        'color' => 'from-cyan-500 to-sky-600',
        'submenu' => [
            ['title' => 'Job Postings', 'route' => 'recruitment.jobs', 'icon' => 'bi-megaphone'],
            ['title' => 'Applications', 'route' => 'recruitment.applications', 'icon' => 'bi-files'],
            ['title' => 'Candidates', 'route' => 'recruitment.candidates', 'icon' => 'bi-person-lines-fill'],
            ['title' => 'Interviews', 'route' => 'recruitment.interviews', 'icon' => 'bi-calendar-week'],
        ]
    ],
    [
        'title' => 'Performance',
        'icon' => 'bi-graph-up',
        'permission' => 'view-performance',
        'badge' => null,
        'color' => 'from-indigo-500 to-blue-600',
        'submenu' => [
            ['title' => 'Reviews', 'route' => 'performance.reviews', 'icon' => 'bi-star'],
            ['title' => 'Goals', 'route' => 'performance.goals', 'icon' => 'bi-bullseye'],
            ['title' => 'Feedback', 'route' => 'performance.feedback', 'icon' => 'bi-chat'],
            ['title' => 'Training', 'route' => 'performance.training', 'icon' => 'bi-mortarboard'],
        ]
    ],
    [
        'title' => 'Reports',
        'icon' => 'bi-pie-chart',
        'route' => 'reports.index',
        'permission' => 'view-reports',
        'badge' => null,
        'color' => 'from-red-500 to-rose-600'
    ],
    [
        'title' => 'Documents',
        'icon' => 'bi-folder2',
        'permission' => 'view-documents',
        'badge' => null,
        'color' => 'from-amber-500 to-yellow-600',
        'submenu' => [
            ['title' => 'Employee Documents', 'route' => 'documents.employees', 'icon' => 'bi-folder'],
            ['title' => 'Company Documents', 'route' => 'documents.company', 'icon' => 'bi-building'],
            ['title' => 'Templates', 'route' => 'documents.templates', 'icon' => 'bi-file-text'],
        ]
    ],
    [
        'title' => 'Settings',
        'icon' => 'bi-gear',
        'permission' => 'manage-settings',
        'badge' => null,
        'color' => 'from-gray-500 to-gray-600',
        'submenu' => [
            ['title' => 'Company Settings', 'route' => 'settings.company', 'icon' => 'bi-building-gear'],
            ['title' => 'User Management', 'route' => 'settings.users', 'icon' => 'bi-people-gear'],
            ['title' => 'Roles & Permissions', 'route' => 'settings.roles', 'icon' => 'bi-shield'],
            ['title' => 'System Settings', 'route' => 'settings.system', 'icon' => 'bi-sliders2'],
            ['title' => 'Email Templates', 'route' => 'settings.emails', 'icon' => 'bi-envelope-paper'],
        ]
    ],
];
@endphp

<!-- Desktop Sidebar -->
<aside class="hidden lg:flex lg:flex-col lg:w-72 lg:fixed lg:inset-y-0 bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 border-r border-gray-200/80 dark:border-gray-700/80 z-30 shadow-xl">
    <!-- Logo Area with Gradient -->
    <div class="flex items-center justify-between h-20 px-6 border-b border-gray-200/80 dark:border-gray-700/80 bg-gradient-to-r from-blue-600 to-blue-700">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
            <div class="bg-white/20 p-2 rounded-xl backdrop-blur-sm group-hover:scale-110 transition-transform duration-200">
                <x-application-logo class="h-6 w-auto fill-current text-white" />
            </div>
            <div>
                <span class="text-xl font-bold text-white block leading-tight">HRMIS</span>
                <span class="text-xs text-blue-100">Aguinaldo, Ifugao</span>
            </div>
        </a>
        <div class="text-white/60 text-xs">
            <i class="bi bi-database"></i>
            <span class="ml-1">v1.0</span>
        </div>
    </div>

    <!-- Search Box with Icon -->
    <div class="p-4 border-b border-gray-200/80 dark:border-gray-700/80">
        <div class="relative group">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-blue-500 transition-colors"></i>
            <input type="text" 
                   placeholder="Quick search..." 
                   class="w-full pl-10 pr-4 py-2.5 text-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
        </div>
    </div>

    <!-- User Quick Info -->
    <div class="px-4 py-3 border-b border-gray-200/80 dark:border-gray-700/80 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-800">
        <div class="flex items-center space-x-3">
            <div class="relative">
                <img class="h-10 w-10 rounded-xl object-cover border-2 border-white dark:border-gray-700 shadow-md" 
                     src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=2563eb&color=fff&bold=true&size=128' }}" 
                     alt="{{ Auth::user()->name }}">
                <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white dark:ring-gray-800"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                    <i class="bi bi-briefcase me-1"></i>
                    {{ Auth::user()->personalDataSheet->employee_id ?? 'Administrator' }}
                </p>
            </div>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-200/50 dark:hover:bg-gray-700 transition-all">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute bottom-full right-0 mb-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 py-1 z-50">
                    <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="bi bi-person me-2"></i> Profile
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a>
                    <hr class="my-1 border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
        @foreach($menuItems as $item)
            @if(isset($item['permission']) && !auth()->user()->can($item['permission']))
                @continue
            @endif

            @if(isset($item['submenu']))
                <!-- Menu Item with Submenu -->
                <div x-data="{ open: {{ request()->routeIs(collect($item['submenu'])->pluck('route')->map(fn($r) => $r . '*')->implode(' or ')) ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200
                                   {{ request()->routeIs(collect($item['submenu'])->pluck('route')->map(fn($r) => $r . '*')->implode(' or ')) 
                                        ? 'bg-gradient-to-r ' . $item['color'] . ' text-white shadow-md' 
                                        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                        <span class="flex items-center">
                            <i class="bi {{ $item['icon'] }} text-lg {{ request()->routeIs(collect($item['submenu'])->pluck('route')->map(fn($r) => $r . '*')->implode(' or ')) ? '' : 'text-gray-400' }}"></i>
                            <span class="ml-3 font-medium">{{ $item['title'] }}</span>
                        </span>
                        <div class="flex items-center space-x-2">
                            @if($item['badge'])
                                <span class="px-2 py-0.5 text-xs bg-white/20 text-white rounded-full">{{ $item['badge'] }}</span>
                            @endif
                            <i class="bi bi-chevron-right text-sm transition-transform duration-200" :class="{ 'rotate-90': open }"></i>
                        </div>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="transform opacity-0 -translate-y-1" 
                         x-transition:enter-end="transform opacity-100 translate-y-0"
                         class="space-y-1 pl-4 mt-1">
                        @foreach($item['submenu'] as $subItem)
                            @can($subItem['permission'] ?? $item['permission'])
                                <a href="{{ route($subItem['route']) }}" 
                                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200
                                          {{ request()->routeIs($subItem['route']) 
                                               ? 'bg-gradient-to-r ' . $item['color'] . ' text-white shadow-md' 
                                               : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-300' }}">
                                    <i class="bi {{ $subItem['icon'] }} text-sm"></i>
                                    <span class="ml-3">{{ $subItem['title'] }}</span>
                                </a>
                            @endcan
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Single Menu Item -->
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200
                          {{ request()->routeIs($item['route']) 
                               ? 'bg-gradient-to-r ' . $item['color'] . ' text-white shadow-md' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <span class="flex items-center">
                        <i class="bi {{ $item['icon'] }} text-lg {{ request()->routeIs($item['route']) ? '' : 'text-gray-400' }}"></i>
                        <span class="ml-3">{{ $item['title'] }}</span>
                    </span>
                    @if($item['badge'])
                        <span class="px-2 py-0.5 text-xs bg-red-500 text-white rounded-full">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endif
        @endforeach
    </nav>

    <!-- Quick Stats Footer -->
    <div class="border-t border-gray-200/80 dark:border-gray-700/80 p-4 bg-gradient-to-b from-transparent to-gray-50/50 dark:to-gray-900/50">
        <div class="grid grid-cols-2 gap-2">
            <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg p-2 text-center">
                <div class="text-xs text-gray-500 dark:text-gray-400">Active</div>
                <div class="text-sm font-bold text-gray-900 dark:text-white">127</div>
            </div>
            <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg p-2 text-center">
                <div class="text-xs text-gray-500 dark:text-gray-400">On Leave</div>
                <div class="text-sm font-bold text-gray-900 dark:text-white">8</div>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar -->
<aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-72 bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 border-r border-gray-200/80 dark:border-gray-700/80 z-40 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden overflow-y-auto shadow-2xl">
    <!-- Mobile Header with Close -->
    <div class="flex items-center justify-between h-20 px-6 border-b border-gray-200/80 dark:border-gray-700/80 bg-gradient-to-r from-blue-600 to-blue-700">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
            <div class="bg-white/20 p-2 rounded-xl">
                <x-application-logo class="h-6 w-auto fill-current text-white" />
            </div>
            <span class="text-xl font-bold text-white">HRMS</span>
        </a>
        <button onclick="toggleMobileMenu()" class="p-2 text-white/80 hover:text-white rounded-lg hover:bg-white/10 transition-all">
            <i class="bi bi-x-lg text-xl"></i>
        </button>
    </div>

    <!-- Mobile Search -->
    <div class="p-4 border-b border-gray-200/80 dark:border-gray-700/80">
        <div class="relative">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2.5 text-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl">
        </div>
    </div>

    <!-- Mobile Navigation -->
    <nav class="px-3 py-4 space-y-1">
        @foreach($menuItems as $item)
            @if(isset($item['permission']) && !auth()->user()->can($item['permission']))
                @continue
            @endif

            @if(isset($item['submenu']))
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span class="flex items-center">
                            <i class="bi {{ $item['icon'] }} text-lg text-gray-400"></i>
                            <span class="ml-3">{{ $item['title'] }}</span>
                        </span>
                        <div class="flex items-center space-x-2">
                            @if($item['badge'])
                                <span class="px-2 py-0.5 text-xs bg-red-500 text-white rounded-full">{{ $item['badge'] }}</span>
                            @endif
                            <i class="bi bi-chevron-right text-sm transition-transform" :class="{ 'rotate-90': open }"></i>
                        </div>
                    </button>
                    <div x-show="open" class="space-y-1 pl-4">
                        @foreach($item['submenu'] as $subItem)
                            <a href="{{ route($subItem['route']) }}" class="flex items-center px-3 py-2 text-sm rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="bi {{ $subItem['icon'] }} text-sm"></i>
                                <span class="ml-3">{{ $subItem['title'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <a href="{{ route($item['route']) }}" class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <span class="flex items-center">
                        <i class="bi {{ $item['icon'] }} text-lg text-gray-400"></i>
                        <span class="ml-3">{{ $item['title'] }}</span>
                    </span>
                    @if($item['badge'])
                        <span class="px-2 py-0.5 text-xs bg-red-500 text-white rounded-full">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endif
        @endforeach
    </nav>

    <!-- Mobile User Info -->
    <div class="absolute bottom-0 left-0 right-0 border-t border-gray-200/80 dark:border-gray-700/80 p-4 bg-gradient-to-t from-gray-50 to-transparent dark:from-gray-900">
        <div class="flex items-center space-x-3">
            <img class="h-10 w-10 rounded-xl object-cover border-2 border-white dark:border-gray-700" 
                 src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=2563eb&color=fff' }}" 
                 alt="">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</aside>