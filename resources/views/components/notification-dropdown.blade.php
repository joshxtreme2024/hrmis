<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open" class="relative p-2.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200">
        <span class="sr-only">View notifications</span>
        <i class="bi bi-bell text-xl"></i>
        <span class="absolute top-1 right-1 block h-2.5 w-2.5 rounded-full bg-gradient-to-r from-red-500 to-rose-500 ring-2 ring-white dark:ring-gray-800 animate-pulse"></span>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 z-50">
        
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="bi bi-bell-fill text-blue-500 me-2"></i>
                Notifications
            </h3>
            <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs px-2 py-1 rounded-lg">12 new</span>
        </div>
        
        <div class="max-h-96 overflow-y-auto">
            <!-- Sample Notifications -->
            <div class="px-4 py-3 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-gray-700 dark:hover:to-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-0 transition-all">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="h-9 w-9 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-md">
                            <i class="bi bi-person-plus text-lg"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">New employee onboarded</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">John Doe joined as Software Engineer</p>
                        <div class="flex items-center mt-1 text-xs text-gray-400 dark:text-gray-500">
                            <i class="bi bi-clock me-1"></i>
                            5 minutes ago
                        </div>
                    </div>
                    <span class="h-2 w-2 bg-blue-500 rounded-full"></span>
                </div>
            </div>

            <div class="px-4 py-3 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 dark:hover:from-gray-700 dark:hover:to-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-0 transition-all">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="h-9 w-9 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center text-white shadow-md">
                            <i class="bi bi-check-lg text-lg"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Leave request approved</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Your annual leave request has been approved</p>
                        <div class="flex items-center mt-1 text-xs text-gray-400 dark:text-gray-500">
                            <i class="bi bi-clock me-1"></i>
                            1 hour ago
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-4 py-3 hover:bg-gradient-to-r hover:from-amber-50 hover:to-yellow-50 dark:hover:from-gray-700 dark:hover:to-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-0 transition-all">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="h-9 w-9 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 flex items-center justify-center text-white shadow-md">
                            <i class="bi bi-clock text-lg"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Timesheet reminder</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Please submit your timesheet for this week</p>
                        <div class="flex items-center mt-1 text-xs text-gray-400 dark:text-gray-500">
                            <i class="bi bi-clock me-1"></i>
                            3 hours ago
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 text-center">
            <a href="{{ route('notifications.index') }}" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center justify-center">
                <span>View all notifications</span>
                <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>