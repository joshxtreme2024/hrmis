@props(['collapsible' => true])

@php
use App\Models\Role;

// Get current user
$user = auth()->user();

// Define helper functions for permission and role checks
function userHasPermission($permissionName) {
    $user = auth()->user();
    if (!$user) return false;
    
    // Admin has all permissions
    if ($user->role === 'admin') return true;
    
    // Get the role instance
    $role = App\Models\Role::where('name', $user->role)->first();
    if (!$role) return false;
    
    // Check if role has the permission
    return $role->permissions()->where('name', $permissionName)->exists();
}

function userHasRole($roleName) {
    $user = auth()->user();
    if (!$user) return false;
    
    if (is_array($roleName)) {
        return in_array($user->role, $roleName);
    }
    return $user->role === $roleName;
}

function userCan($permission) {
    if (!$permission) return true;
    return userHasPermission($permission);
}

// Define menu items with both role and permission checks
$menuItems = [
    [
        'title' => 'Dashboard',
        'icon' => 'bi-speedometer2',
        'route' => 'dashboard',
        'permission' => null,
        'role' => null,
        'badge' => null,
        'color' => 'from-blue-500 to-blue-600'
    ],
    [
        'title' => 'My PDS Profile',
        'icon' => 'bi bi-person-circle',
        'route' => 'myprofile.show',
        'permission' => null,
        'role' => null,
        'badge' => null,
        'color' => 'from-blue-500 to-blue-600'
    ],
    [
        'title' => 'Employee Management',
        'icon' => 'bi-people',
        'permission' => 'view-employees',
        'role' => ['admin', 'hr'],
        'badge' => null,
        'color' => 'from-emerald-500 to-teal-600',
        'submenu' => [
            ['title' => 'All Employees', 'route' => 'employees.index', 'icon' => 'bi-person-badge', 'permission' => 'view-employees'],
            ['title' => 'Departments', 'route' => 'departments.index', 'icon' => 'bi-building', 'permission' => 'view-departments'],
            ['title' => 'Positions', 'route' => 'positions.index', 'icon' => 'bi-briefcase', 'permission' => 'view-positions'],
            ['title' => 'Employment Types', 'route' => 'employment-types.index', 'icon' => 'bi-tags', 'permission' => 'view-employment-types'],
        ]
    ],
    [
        'title' => 'Attendance',
        'icon' => 'bi-clock-history',
        'permission' => 'view-attendance',
        'role' => ['admin', 'hr', 'employee'],
        'badge' => null,
        'color' => 'from-orange-500 to-amber-600',
        'submenu' => [
            ['title' => 'Daily Attendance', 'route' => 'attendance.daily', 'icon' => 'bi-calendar-day', 'permission' => 'view-attendance'],
            ['title' => 'Timesheets', 'route' => 'attendance.timesheets', 'icon' => 'bi-file-text', 'permission' => 'view-timesheets'],
            ['title' => 'Shifts', 'route' => 'shifts.index', 'icon' => 'bi-arrow-repeat', 'permission' => 'view-shifts'],
            ['title' => 'Overtime', 'route' => 'overtime.index', 'icon' => 'bi-clock', 'permission' => 'view-overtime'],
        ]
    ],
    [
        'title' => 'Leave Mngt.',
        'icon' => 'bi-calendar-check',
        'permission' => 'view-leave',
        'role' => ['admin', 'hr', 'employee'],
        'badge' => '8 requests',
        'color' => 'from-purple-500 to-pink-600',
        'submenu' => [
            ['title' => 'Leave Requests', 'route' => 'leave.requests', 'icon' => 'bi-inbox', 'permission' => 'view-leave', 'role' => ['admin', 'hr']],
            ['title' => 'Leave Calendar', 'route' => 'leave.calendar', 'icon' => 'bi-calendar3', 'permission' => 'view-leave'],
            ['title' => 'Leave Types', 'route' => 'leave.types', 'icon' => 'bi-tag', 'permission' => 'view-leave-types', 'role' => ['admin', 'hr']],
            ['title' => 'My Leaves', 'route' => 'leave.my-requests', 'icon' => 'bi-person-check', 'permission' => null],
        ]
    ],
    [
        'title' => 'Payroll',
        'icon' => 'bi-cash-stack',
        'permission' => 'view-payroll',
        'role' => ['admin', 'hr'],
        'badge' => null,
        'color' => 'from-green-500 to-emerald-600',
        'submenu' => [
            ['title' => 'Salary Management', 'route' => 'payroll.salaries', 'icon' => 'bi-cash', 'permission' => 'view-payroll'],
            ['title' => 'Payslips', 'route' => 'payroll.payslips', 'icon' => 'bi-file-earmark-text', 'permission' => 'view-payslips'],
            ['title' => 'Tax Information', 'route' => 'payroll.tax', 'icon' => 'bi-percent', 'permission' => 'view-tax'],
            ['title' => 'Benefits', 'route' => 'payroll.benefits', 'icon' => 'bi-gift', 'permission' => 'view-benefits'],
        ]
    ],
    [
        'title' => 'Recruitment',
        'icon' => 'bi-person-plus',
        'permission' => 'view-recruitment',
        'role' => ['admin', 'hr'],
        'badge' => '5 new',
        'color' => 'from-cyan-500 to-sky-600',
        'submenu' => [
            ['title' => 'Job Postings', 'route' => 'recruitment.jobs', 'icon' => 'bi-megaphone', 'permission' => 'view-jobs'],
            ['title' => 'Applications', 'route' => 'recruitment.applications', 'icon' => 'bi-files', 'permission' => 'view-applications'],
            ['title' => 'Candidates', 'route' => 'recruitment.candidates', 'icon' => 'bi-person-lines-fill', 'permission' => 'view-candidates'],
            ['title' => 'Interviews', 'route' => 'recruitment.interviews', 'icon' => 'bi-calendar-week', 'permission' => 'view-interviews'],
        ]
    ],
    [
        'title' => 'Performance',
        'icon' => 'bi-graph-up',
        'permission' => 'view-performance',
        'role' => ['admin', 'hr'],
        'badge' => null,
        'color' => 'from-indigo-500 to-blue-600',
        'submenu' => [
            ['title' => 'Reviews', 'route' => 'performance.reviews', 'icon' => 'bi-star', 'permission' => 'view-reviews'],
            ['title' => 'Goals', 'route' => 'performance.goals', 'icon' => 'bi-bullseye', 'permission' => 'view-goals'],
            ['title' => 'Feedback', 'route' => 'performance.feedback', 'icon' => 'bi-chat', 'permission' => 'view-feedback'],
            ['title' => 'Training', 'route' => 'performance.training', 'icon' => 'bi-mortarboard', 'permission' => 'view-training'],
        ]
    ],
    [
        'title' => 'Reports',
        'icon' => 'bi-pie-chart',
        'route' => 'reports.index',
        'permission' => 'view-reports',
        'role' => ['admin', 'hr'],
        'badge' => null,
        'color' => 'from-red-500 to-rose-600'
    ],
    [
        'title' => 'Documents',
        'icon' => 'bi-folder2',
        'permission' => 'view-documents',
        'role' => ['admin', 'hr'],
        'badge' => null,
        'color' => 'from-amber-500 to-yellow-600',
        'submenu' => [
            ['title' => 'Employee Documents', 'route' => 'employee.documents.index', 'icon' => 'bi-folder', 'permission' => 'view-employee-documents'],
            ['title' => 'Company Documents', 'route' => 'company.documents.index', 'icon' => 'bi-building', 'permission' => 'view-company-documents'],
            ['title' => 'Templates', 'route' => 'templates.documents.index', 'icon' => 'bi-file-text', 'permission' => 'view-templates'],
        ]
    ],
    [
        'title' => 'Settings',
        'icon' => 'bi-gear',
        'permission' => 'manage-settings',
        'role' => ['admin'],
        'badge' => null,
        'color' => 'from-gray-500 to-gray-600',
        'submenu' => [
            ['title' => 'User Management', 'route' => 'users.index', 'icon' => 'bi-person-gear', 'permission' => 'manage-users'],
            ['title' => 'Manage Departments', 'route' => 'departments.index', 'icon' => 'bi-building-gear', 'permission' => 'manage-departments'],
            ['title' => 'Office Positions', 'route' => 'positions.index', 'icon' => 'bi-house-gear', 'permission' => 'manage-positions'],
            ['title' => 'Roles & Permissions', 'route' => 'roles.index', 'icon' => 'bi-shield', 'permission' => 'manage-roles'],
            ['title' => 'System Settings', 'route' => 'settings.system', 'icon' => 'bi-sliders2', 'permission' => 'manage-system'],
        ]
    ],
];

// Filter menu items based on role and permission
function shouldShowMenuItem($item) {
    $user = auth()->user();
    
    // 🔥 ADMIN BYPASS - Admin sees everything
    if ($user && $user->role === 'admin') {
        return true;
    }
    
    // Check role first (for non-admin users)
    if (isset($item['role']) && $item['role']) {
        if (!userHasRole($item['role'])) {
            return false;
        }
    }
    
    // Check permission (for non-admin users)
    if (isset($item['permission']) && $item['permission']) {
        if (!userCan($item['permission'])) {
            return false;
        }
    }
    
    return true;
}

// Filter all menu items
$filteredMenuItems = array_filter($menuItems, function($item) {
    return shouldShowMenuItem($item);
});

// Filter submenu items
foreach ($filteredMenuItems as &$item) {
    if (isset($item['submenu'])) {
        $item['submenu'] = array_filter($item['submenu'], function($subItem) {
            return shouldShowMenuItem($subItem);
        });
    }
}

unset($item);

// Count visible items
$visibleMenuItems = count($filteredMenuItems);
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

    <!-- Debug Info (Remove in production) -->
    @if(config('app.debug'))
    <div class="px-4 py-2 bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-xs">
        <div class="text-gray-500 dark:text-gray-400">
            <span class="font-semibold">Role:</span> {{ auth()->user()->role ?? 'None' }}
            <span class="ml-2 font-semibold">Permissions:</span> 
            @php
                $role = App\Models\Role::where('name', auth()->user()->role)->first();
                $perms = $role ? $role->permissions()->pluck('name')->toArray() : [];
            @endphp
            {{ implode(', ', array_slice($perms, 0, 3)) }}
            @if(count($perms) > 3)
                +{{ count($perms) - 3 }} more
            @endif
        </div>
    </div>
    @endif

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
        @forelse($filteredMenuItems as $item)
            @if(isset($item['submenu']) && count($item['submenu']) > 0)
                <!-- Menu Item with Submenu -->
                @php
                    // Check if any submenu route matches the current route
                    $isActive = false;
                    foreach($item['submenu'] as $subItem) {
                        if(request()->routeIs($subItem['route'] . '*') || request()->routeIs($subItem['route'])) {
                            $isActive = true;
                            break;
                        }
                    }
                @endphp
                <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200
                                   {{ $isActive 
                                        ? 'bg-gradient-to-r ' . $item['color'] . ' text-white shadow-md' 
                                        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                        <span class="flex items-center">
                            <i class="bi {{ $item['icon'] }} text-lg {{ $isActive ? '' : 'text-gray-400' }}"></i>
                            <span class="ml-3 font-medium">{{ $item['title'] }}</span>
                        </span>
                        <div class="flex items-center space-x-2">
                            @if($item['badge'])
                                <span class="px-2 py-0.5 text-xs bg-white/20 text-red-500 rounded-full">{{ $item['badge'] }}</span>
                            @endif
                            <i class="bi bi-chevron-right text-sm transition-transform duration-200" :class="{ 'rotate-90': open }"></i>
                        </div>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="transform opacity-0 -translate-y-1" 
                         x-transition:enter-end="transform opacity-100 translate-y-0"
                         class="space-y-1 pl-4 mt-1">
                        @foreach($item['submenu'] as $subItem)
                            @php
                                $isSubActive = request()->routeIs($subItem['route'] . '*') || request()->routeIs($subItem['route']);
                            @endphp
                            <a href="{{ route($subItem['route']) }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200
                                      {{ $isSubActive 
                                           ? 'bg-gradient-to-r ' . $item['color'] . ' text-white shadow-md' 
                                           : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-300' }}">
                                <i class="bi {{ $subItem['icon'] }} text-sm"></i>
                                <span class="ml-3">{{ $subItem['title'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @elseif(isset($item['route']))
                <!-- Single Menu Item -->
                @php
                    $isActive = request()->routeIs($item['route']);
                @endphp
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200
                          {{ $isActive 
                               ? 'bg-gradient-to-r ' . $item['color'] . ' text-white shadow-md' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <span class="flex items-center">
                        <i class="bi {{ $item['icon'] }} text-lg {{ $isActive ? '' : 'text-gray-400' }}"></i>
                        <span class="ml-3">{{ $item['title'] }}</span>
                    </span>
                    @if($item['badge'])
                        <span class="px-2 py-0.5 text-xs bg-red-500 text-white rounded-full">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endif
        @empty
            <!-- No menu items available -->
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="bi bi-exclamation-circle text-3xl mb-3 block"></i>
                <p class="text-sm font-medium">No menu items available</p>
                <p class="text-xs mt-1">Contact your administrator for access</p>
            </div>
        @endforelse

        <!-- Fallback menu items if all are filtered out -->
        @if($visibleMenuItems === 0)
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                    <i class="bi bi-speedometer2 text-lg text-gray-400"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('myprofile.show') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                    <i class="bi bi-person text-lg text-gray-400"></i>
                    <span class="ml-3">Profile</span>
                </a>
            </div>
        @endif
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

    <!-- Mobile Navigation -->
    <nav class="px-3 py-4 space-y-1">
        @foreach($filteredMenuItems as $item)
            @if(isset($item['submenu']) && count($item['submenu']) > 0)
                @php
                    // Check if any submenu route matches the current route
                    $isMobileActive = false;
                    foreach($item['submenu'] as $subItem) {
                        if(request()->routeIs($subItem['route'] . '*') || request()->routeIs($subItem['route'])) {
                            $isMobileActive = true;
                            break;
                        }
                    }
                @endphp
                <div x-data="{ open: {{ $isMobileActive ? 'true' : 'false' }} }" class="space-y-1">
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
                            @php
                                $isSubActive = request()->routeIs($subItem['route'] . '*') || request()->routeIs($subItem['route']);
                            @endphp
                            <a href="{{ route($subItem['route']) }}" 
                               class="flex items-center px-3 py-2 text-sm rounded-lg 
                                      {{ $isSubActive 
                                           ? 'bg-gradient-to-r ' . $item['color'] . ' text-white shadow-md' 
                                           : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <i class="bi {{ $subItem['icon'] }} text-sm"></i>
                                <span class="ml-3">{{ $subItem['title'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @elseif(isset($item['route']))
                @php
                    $isActive = request()->routeIs($item['route']);
                @endphp
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl
                          {{ $isActive 
                               ? 'bg-gradient-to-r ' . $item['color'] . ' text-white shadow-md' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
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
                 src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?email='.urlencode(Auth::user()->email).'&background=2563eb&color=fff' }}" 
                 alt="">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->email }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Role: {{ ucfirst(Auth::user()->role ?? 'User') }}</p>
            </div>
        </div>
    </div>
</aside>

<script>
function toggleMobileMenu() {
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('mobile-menu-overlay');
    if (sidebar) {
        sidebar.classList.toggle('-translate-x-full');
    }
    if (overlay) {
        overlay.classList.toggle('hidden');
    }
}
</script>