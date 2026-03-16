<nav x-data="{ open: false, showUserMenu: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <button @click="open = !open" class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" :class="{'hidden': open, 'block': !open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" :class="{'block': open, 'hidden': !open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Logo -->
                <div class="shrink-0 flex items-center ml-2 sm:ml-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-blue-600 dark:text-blue-400" />
                        <span class="hidden sm:block text-lg font-semibold text-gray-800 dark:text-white">HRMS</span>
                    </a>
                </div>

                <!-- Main Navigation Links -->
                <div class="hidden sm:ml-10 sm:flex sm:items-center sm:space-x-1">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fa-regular fa-gauge-high mr-2"></i>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @can('view-employees')
                    <x-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')" class="px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fa-regular fa-users mr-2"></i>
                        {{ __('Employees') }}
                    </x-nav-link>
                    @endcan

                    @can('view-departments')
                    <x-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.*')" class="px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fa-regular fa-building mr-2"></i>
                        {{ __('Departments') }}
                    </x-nav-link>
                    @endcan

                    @can('view-attendance')
                    <x-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')" class="px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fa-regular fa-clock mr-2"></i>
                        {{ __('Attendance') }}
                    </x-nav-link>
                    @endcan

                    @can('view-leave')
                    <x-nav-link :href="route('leave.index')" :active="request()->routeIs('leave.*')" class="px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fa-regular fa-calendar-check mr-2"></i>
                        {{ __('Leave') }}
                    </x-nav-link>
                    @endcan

                    @can('view-payroll')
                    <x-nav-link :href="route('payroll.index')" :active="request()->routeIs('payroll.*')" class="px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fa-regular fa-file-invoice-dollar mr-2"></i>
                        {{ __('Payroll') }}
                    </x-nav-link>
                    @endcan

                    @can('view-reports')
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fa-regular fa-chart-line mr-2"></i>
                        {{ __('Reports') }}
                    </x-nav-link>
                    @endcan

                    @can('manage-settings')
                    <x-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')" class="px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fa-regular fa-gear mr-2"></i>
                        {{ __('Settings') }}
                    </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Right Side - Quick Actions & User Menu -->
            <div class="hidden sm:flex sm:items-center sm:space-x-3">
                <!-- Quick Actions -->
                <div class="flex items-center space-x-2">
                    @can('create-employee')
                    <a href="{{ route('employees.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        <i class="fa-regular fa-plus mr-2"></i>
                        <span class="hidden lg:inline">Add Employee</span>
                    </a>
                    @endcan

                    <!-- Help Button -->
                    <button class="p-2 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none" title="Help">
                        <i class="fa-regular fa-circle-question text-xl"></i>
                    </button>
                </div>

                <!-- Notification Dropdown -->
                <x-notification-dropdown />

                <!-- User Menu Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none group">
                        <img class="h-8 w-8 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700 group-hover:border-blue-500 transition" 
                             src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF&size=128' }}" 
                             alt="{{ Auth::user()->name }}">
                        <div class="hidden lg:block text-left">
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->employee_id ?? 'Employee' }}</div>
                        </div>
                        <svg class="hidden lg:block h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-50">

                        <!-- User Info Header -->
                        <div class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                            <div class="mt-2 flex items-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ Auth::user()->role ?? 'Employee' }}
                                </span>
                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                    Last login: {{ Auth::user()->last_login_at?->diffForHumans() ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-1">
                            <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fa-regular fa-user w-5 text-gray-400"></i>
                                <span class="ml-3">My Profile</span>
                            </a>

                            <a href="{{ route('attendance.my-timesheet') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fa-regular fa-clock w-5 text-gray-400"></i>
                                <span class="ml-3">My Timesheet</span>
                            </a>

                            <a href="{{ route('leave.my-requests') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fa-regular fa-calendar w-5 text-gray-400"></i>
                                <span class="ml-3">Leave Requests</span>
                                <span class="ml-auto bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full">3</span>
                            </a>

                            <a href="{{ route('payroll.my-payslips') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fa-regular fa-file-invoice w-5 text-gray-400"></i>
                                <span class="ml-3">Payslips</span>
                            </a>

                            <a href="{{ route('documents.my-documents') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fa-regular fa-folder w-5 text-gray-400"></i>
                                <span class="ml-3">My Documents</span>
                            </a>
                        </div>

                        <!-- Settings & Logout -->
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fa-regular fa-gear w-5 text-gray-400"></i>
                                <span class="ml-3">Account Settings</span>
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fa-regular fa-arrow-right-from-bracket w-5 text-gray-400"></i>
                                    <span class="ml-3">Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="sm:hidden">
        
        <div class="pt-2 pb-3 space-y-1 bg-white dark:bg-gray-800">
            <!-- Mobile Navigation Links -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="fa-regular fa-gauge-high w-6"></i>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @can('view-employees')
            <x-responsive-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">
                <i class="fa-regular fa-users w-6"></i>
                {{ __('Employees') }}
            </x-responsive-nav-link>
            @endcan

            @can('view-departments')
            <x-responsive-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.*')">
                <i class="fa-regular fa-building w-6"></i>
                {{ __('Departments') }}
            </x-responsive-nav-link>
            @endcan

            @can('view-attendance')
            <x-responsive-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')">
                <i class="fa-regular fa-clock w-6"></i>
                {{ __('Attendance') }}
            </x-responsive-nav-link>
            @endcan

            @can('view-leave')
            <x-responsive-nav-link :href="route('leave.index')" :active="request()->routeIs('leave.*')">
                <i class="fa-regular fa-calendar-check w-6"></i>
                {{ __('Leave') }}
            </x-responsive-nav-link>
            @endcan

            @can('view-payroll')
            <x-responsive-nav-link :href="route('payroll.index')" :active="request()->routeIs('payroll.*')">
                <i class="fa-regular fa-file-invoice-dollar w-6"></i>
                {{ __('Payroll') }}
            </x-responsive-nav-link>
            @endcan

            @can('view-reports')
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                <i class="fa-regular fa-chart-line w-6"></i>
                {{ __('Reports') }}
            </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Mobile User Menu -->
        <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" 
                         src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF&size=128' }}" 
                         alt="">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                </div>
                <button class="ml-auto text-gray-400 hover:text-gray-500">
                    <i class="fa-regular fa-bell text-xl"></i>
                </button>
            </div>
            
            <div class="mt-3 space-y-1 px-2">
                <x-responsive-nav-link :href="route('profile.show')">
                    <i class="fa-regular fa-user w-6"></i>
                    {{ __('My Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('attendance.my-timesheet')">
                    <i class="fa-regular fa-clock w-6"></i>
                    {{ __('My Timesheet') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('leave.my-requests')">
                    <i class="fa-regular fa-calendar w-6"></i>
                    {{ __('Leave Requests') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('profile.edit')">
                    <i class="fa-regular fa-gear w-6"></i>
                    {{ __('Settings') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fa-regular fa-arrow-right-from-bracket w-6"></i>
                        {{ __('Sign Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>