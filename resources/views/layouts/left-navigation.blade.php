@props(['collapsible' => true])

@php
    /* ---------------------------------------------------------------
     | Who is viewing, and what may they see? (one query, not one per menu item)
     * ------------------------------------------------------------- */
    $user    = auth()->user();
    $isAdmin = $user?->role === 'admin';
    $role    = $user ? \App\Models\Role::where('name', $user->role)->first() : null;
    $perms   = $role ? $role->permissions()->pluck('name')->all() : [];

    $can     = fn ($p) => !$p || $isAdmin || in_array($p, $perms, true);
    $hasRole = fn ($r) => !$r || $isAdmin || in_array($user?->role, (array) $r, true);
    $visible = fn ($i) => $hasRole($i['role'] ?? null) && $can($i['permission'] ?? null) && \Illuminate\Support\Facades\Route::has($i['route']);

    // People whose approved leave covers today (shown to HR/admin only)
    $leaveBadge = in_array($user?->role, ['admin', 'hr'], true)
        ? \App\Models\LeaveApplication::whereDate('date_from', '<=', now())->whereDate('date_to', '>=', now())->count()
        : 0;

    /* ---------------------------------------------------------------
     | Menu definition. 'group' = section heading, 'match' = extra route patterns that keep the item highlighted.
     * ------------------------------------------------------------- */
    $menu = [
        ['group' => 'Main', 'title' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'dashboard'],
        ['group' => 'Main', 'title' => 'My PDS Profile', 'icon' => 'bi-person-circle', 'route' => 'myprofile.show', 'match' => ['myprofile.*']],
        ['group' => 'Main', 'title' => 'My 201 File', 'icon' => 'bi-folder2-open', 'route' => 'mydocuments.index', 'match' => ['mydocuments.*']],

        ['group' => 'Workforce', 'title' => 'Employee Management', 'icon' => 'bi-people', 'permission' => 'view-employees', 'role' => ['admin', 'hr'], 'submenu' => [
            ['title' => 'All Employees', 'route' => 'employees.index', 'permission' => 'view-employees'],
        ]],
        ['group' => 'Workforce', 'title' => 'Attendance', 'icon' => 'bi-clock-history', 'permission' => 'view-attendance', 'role' => ['admin', 'hr', 'employee'], 'submenu' => [
            ['title' => 'Daily Attendance', 'route' => 'attendance.daily', 'permission' => 'view-attendance'],
            ['title' => 'Timesheets', 'route' => 'attendance.timesheets', 'permission' => 'view-timesheets'],
            ['title' => 'Shifts', 'route' => 'shifts.index', 'permission' => 'view-shifts'],
            ['title' => 'Overtime', 'route' => 'overtime.index', 'permission' => 'view-overtime'],
        ]],
        ['group' => 'Workforce', 'title' => 'Leave Management', 'icon' => 'bi-calendar-check', 'permission' => 'view-leave', 'role' => ['admin', 'hr', 'employee'], 'badge' => $leaveBadge, 'badgeLabel' => 'on leave today', 'submenu' => [
            ['title' => 'Leave Requests', 'route' => 'leave-applications.index', 'permission' => 'view-leave', 'role' => ['admin', 'hr']],
            ['title' => 'Leave Ledgers', 'route' => 'leave-ledgers.index', 'permission' => 'view-leave', 'role' => ['admin', 'hr']],
            ['title' => 'Leave Calendar', 'route' => 'leave-calendar.index', 'permission' => 'view-leave', 'role' => ['admin', 'hr']],
            ['title' => 'Department Calendar', 'route' => 'department-leave-calendar.index', 'role' => ['admin', 'hr', 'department_head']],
            ['title' => 'My Leaves', 'route' => 'my-leaves.index'],
            ['title' => 'My Leave Ledger', 'route' => 'my-leave-ledger.index'],
            ['title' => 'My Leave Calendar', 'route' => 'my-leave-calendar.index'],
        ]],

        ['group' => 'Administration', 'title' => 'Payroll', 'icon' => 'bi-cash-stack', 'permission' => 'view-payroll', 'role' => ['admin', 'hr'], 'submenu' => [
            ['title' => 'Salary Management', 'route' => 'payroll.salaries', 'permission' => 'view-payroll'],
            ['title' => 'Payslips', 'route' => 'payroll.payslips', 'permission' => 'view-payslips'],
            ['title' => 'Tax Information', 'route' => 'payroll.tax', 'permission' => 'view-tax'],
            ['title' => 'Benefits', 'route' => 'payroll.benefits', 'permission' => 'view-benefits'],
        ]],
        ['group' => 'Administration', 'title' => 'Recruitment', 'icon' => 'bi-person-plus', 'permission' => 'view-recruitment', 'role' => ['admin', 'hr'], 'submenu' => [
            ['title' => 'Job Postings', 'route' => 'recruitment.jobs', 'permission' => 'view-jobs'],
            ['title' => 'Applications', 'route' => 'recruitment.applications', 'permission' => 'view-applications'],
            ['title' => 'Candidates', 'route' => 'recruitment.candidates', 'permission' => 'view-candidates'],
            ['title' => 'Interviews', 'route' => 'recruitment.interviews', 'permission' => 'view-interviews'],
        ]],
        ['group' => 'Administration', 'title' => 'Performance', 'icon' => 'bi-graph-up', 'permission' => 'view-performance', 'role' => ['admin', 'hr'], 'submenu' => [
            ['title' => 'Reviews', 'route' => 'performance.reviews', 'permission' => 'view-reviews'],
            ['title' => 'Goals', 'route' => 'performance.goals', 'permission' => 'view-goals'],
            ['title' => 'Feedback', 'route' => 'performance.feedback', 'permission' => 'view-feedback'],
            ['title' => 'Training', 'route' => 'performance.training', 'permission' => 'view-training'],
        ]],
        ['group' => 'Administration', 'title' => 'Reports', 'icon' => 'bi-pie-chart', 'route' => 'reports.index', 'permission' => 'view-reports', 'role' => ['admin', 'hr']],
        ['group' => 'Administration', 'title' => 'Documents', 'icon' => 'bi-folder2', 'permission' => 'view-documents', 'role' => ['admin', 'hr'], 'submenu' => [
            ['title' => 'Employee Documents', 'route' => 'employee.documents.index', 'permission' => 'view-employee-documents'],
            ['title' => 'Company Documents', 'route' => 'company.documents.index', 'permission' => 'view-company-documents'],
            ['title' => 'Templates', 'route' => 'templates.documents.index', 'permission' => 'view-templates'],
        ]],
        ['group' => 'System', 'title' => 'Settings', 'icon' => 'bi-gear', 'permission' => 'manage-settings', 'role' => ['admin'], 'submenu' => [
            ['title' => 'User Management', 'route' => 'users.index', 'permission' => 'manage-users'],
            ['title' => 'Leave Types', 'route' => 'leave-types.index', 'permission' => 'view-leave-types', 'role' => ['admin', 'hr']],
            ['title' => 'Departments', 'route' => 'departments.index', 'permission' => 'manage-departments'],
            ['title' => 'Office Positions', 'route' => 'positions.index', 'permission' => 'manage-positions'],
            ['title' => 'Roles & Permissions', 'route' => 'roles.index', 'permission' => 'manage-roles'],
            ['title' => 'System Settings', 'route' => 'settings.system', 'permission' => 'manage-system'],
        ]],
    ];

    /* ---------------------------------------------------------------
     | Filter by role, permission and whether the route exists; group under headings.
     * ------------------------------------------------------------- */
    $groups = [];
    foreach ($menu as $item) {
        if (!$hasRole($item['role'] ?? null) || !$can($item['permission'] ?? null)) continue;
        if (isset($item['submenu'])) {
            $item['submenu'] = array_values(array_filter($item['submenu'], $visible));
            if (!$item['submenu']) continue;
            if (count($item['submenu']) === 1) {            // one child: link straight to it
                $item['route'] = $item['submenu'][0]['route'];
                unset($item['submenu']);
            }
        } elseif (!\Illuminate\Support\Facades\Route::has($item['route'])) {
            continue;
        }
        $groups[$item['group']][] = $item;
    }

    // "employees.index" is active on employees.show/edit too; other routes must match exactly.
    $patterns = fn ($i) => $i['match'] ?? [str_ends_with($i['route'], '.index') ? \Illuminate\Support\Str::beforeLast($i['route'], '.') . '.*' : $i['route']];
    $isActive = fn ($i) => request()->routeIs(...$patterns($i));

    $focus   = 'focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-300';
    $initial = strtoupper(substr($user?->email ?? '?', 0, 1));
@endphp

<style>
    [x-cloak] { display: none !important; }
    .hrmis-nav { scrollbar-width: thin; scrollbar-color: rgb(255 255 255 / .25) transparent; }
    @media (prefers-reduced-motion: reduce) { #mobile-sidebar, #mobile-sidebar * { transition: none !important; } }
</style>

<aside id="mobile-sidebar" aria-label="Main navigation"
       class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col bg-gradient-to-b from-[#0a2463] to-[#06173f] text-blue-100 shadow-2xl transition-transform duration-300 ease-in-out lg:z-30 lg:translate-x-0">

    {{-- Brand --}}
    <div class="flex h-20 shrink-0 items-center justify-between border-b border-white/10 px-5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-lg {{ $focus }}">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/20">
                <x-application-logo class="h-6 w-auto fill-current text-white" />
            </span>
            <span class="leading-tight">
                <span class="block text-lg font-semibold tracking-wide text-white">HRMIS</span>
                <span class="block text-xs text-blue-200">Aguinaldo, Ifugao</span>
            </span>
        </a>
        <button type="button" onclick="toggleMobileMenu()" class="rounded-lg p-2 text-blue-200 hover:bg-white/10 hover:text-white lg:hidden {{ $focus }}" aria-label="Close menu">
            <i class="bi bi-x-lg text-lg" aria-hidden="true"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="hrmis-nav flex-1 space-y-6 overflow-y-auto px-3 py-5" aria-label="Sections">
        @forelse($groups as $heading => $items)
            <div>
                <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-blue-300/70">{{ $heading }}</p>
                <ul class="space-y-1">
                    @foreach($items as $item)
                        @php
                            $hasSub = isset($item['submenu']);
                            $active = $hasSub ? collect($item['submenu'])->contains(fn ($s) => $isActive($s)) : $isActive($item);
                            $badge  = (int) ($item['badge'] ?? 0);
                        @endphp
                        <li>
                            @if($hasSub)
                                <div x-data="{ open: {{ $active ? 'true' : 'false' }} }">
                                    <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="sub-{{ \Illuminate\Support\Str::slug($item['title']) }}"
                                            class="group flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $focus }}
                                                   {{ $active ? 'bg-white/10 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                                        <span class="flex items-center gap-3">
                                            <i class="bi {{ $item['icon'] }} w-5 text-center text-base {{ $active ? 'text-sky-300' : 'text-blue-300 group-hover:text-sky-300' }}" aria-hidden="true"></i>
                                            {{ $item['title'] }}
                                        </span>
                                        <span class="flex items-center gap-2">
                                            @if($badge > 0)
                                                <span class="rounded-full bg-amber-400 px-2 py-0.5 text-xs font-semibold text-gray-900" title="{{ $badge }} {{ $item['badgeLabel'] ?? '' }}">{{ $badge }}<span class="sr-only"> {{ $item['badgeLabel'] ?? '' }}</span></span>
                                            @endif
                                            <i class="bi bi-chevron-right text-xs text-blue-300 transition-transform duration-200" :class="{ 'rotate-90': open }" aria-hidden="true"></i>
                                        </span>
                                    </button>
                                    <ul id="sub-{{ \Illuminate\Support\Str::slug($item['title']) }}" x-show="open" x-cloak
                                        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                        class="ml-[1.4rem] mt-1 space-y-0.5 border-l border-white/15 pl-3">
                                        @foreach($item['submenu'] as $sub)
                                            @php $subActive = $isActive($sub); @endphp
                                            <li>
                                                <a href="{{ route($sub['route']) }}" @if($subActive) aria-current="page" @endif
                                                   class="block rounded-md px-3 py-2 text-sm transition-colors {{ $focus }}
                                                          {{ $subActive ? 'bg-white font-semibold text-blue-800 shadow-sm' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                                                    {{ $sub['title'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <a href="{{ route($item['route']) }}" @if($active) aria-current="page" @endif
                                   class="group flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $focus }}
                                          {{ $active ? 'bg-white text-blue-800 shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                                    <span class="flex items-center gap-3">
                                        <i class="bi {{ $item['icon'] }} w-5 text-center text-base {{ $active ? 'text-blue-600' : 'text-blue-300 group-hover:text-sky-300' }}" aria-hidden="true"></i>
                                        {{ $item['title'] }}
                                    </span>
                                    @if($badge > 0)<span class="rounded-full bg-amber-400 px-2 py-0.5 text-xs font-semibold text-gray-900">{{ $badge }}</span>@endif
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <div class="px-3 py-8 text-center text-blue-200">
                <i class="bi bi-lock text-3xl" aria-hidden="true"></i>
                <p class="mt-2 text-sm font-medium text-white">No menu items available</p>
                <p class="mt-1 text-xs">Ask your administrator for access.</p>
            </div>
        @endforelse
    </nav>

    {{-- Signed-in user --}}
    @if($user)
        <a href="{{ route('myprofile.show') }}" class="m-3 flex shrink-0 items-center gap-3 rounded-xl bg-white/10 p-3 transition-colors hover:bg-white/15 {{ $focus }}">
            @if(!empty($user->profile_photo_url))
                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white/30" src="{{ $user->profile_photo_url }}" alt="">
            @else
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-400 text-sm font-semibold text-[#0a2463]" aria-hidden="true">{{ $initial }}</span>
            @endif
            <span class="min-w-0">
                <span class="block truncate text-sm font-medium text-white">{{ $user->email }}</span>
                <span class="block truncate text-xs text-blue-200">{{ ucfirst(str_replace('_', ' ', $user->role ?? 'user')) }}</span>
            </span>
        </a>
    @endif
</aside>

<script>
function toggleMobileMenu() {
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('mobile-menu-overlay');
    if (sidebar) sidebar.classList.toggle('-translate-x-full');
    if (overlay) overlay.classList.toggle('hidden');
}
// Escape closes the menu on mobile
document.addEventListener('keydown', e => {
    const sidebar = document.getElementById('mobile-sidebar');
    if (e.key === 'Escape' && sidebar && !sidebar.classList.contains('-translate-x-full') && window.innerWidth < 1024) toggleMobileMenu();
});
</script>