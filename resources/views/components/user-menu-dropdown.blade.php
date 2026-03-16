<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none group">
        <img class="h-8 w-8 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700 group-hover:border-blue-500 transition" 
             src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF&size=128' }}" 
             alt="{{ Auth::user()->name }}">
        <div class="hidden lg:block text-left">
            <div class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email ?? 'Employee' }}</div>
        </div>
        <i class="bi-chevron-down text-xs text-gray-400"></i>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-50">

        <div class="px-4 py-3">
            <!-- <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p> -->
            <div class="mt-2 flex items-center">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                    Role: {{ Auth::user()->role ?? 'Employee' }}
                </span>
            </div>
        </div>

        <div class="py-1">
            <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="bi bi-person w-5 text-gray-400"></i>
                <span class="ml-3">My Profile</span>
            </a>
            <a href="{{ route('attendance.my-timesheet') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="bi bi-clock w-5 text-gray-400"></i>
                <span class="ml-3">My Timesheet</span>
            </a>
            <a href="{{ route('leave.my-requests') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="bi bi-calendar w-5 text-gray-400"></i>
                <span class="ml-3">Leave Requests</span>
            </a>
        </div>

        <div class="py-1">
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="bi-gear w-5 text-gray-400"></i>
                <span class="ml-3">Account Settings</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="bi-box-arrow-right w-5"></i>
                    <span class="ml-3">Sign Out</span>
                </button>
            </form>
        </div>
    </div>
</div>