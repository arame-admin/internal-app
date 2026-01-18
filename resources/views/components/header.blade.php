<!-- Top Navigation Bar -->
<nav class="fixed top-0 left-0 right-0 h-16 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm z-50">
    <div class="h-full px-6 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <span class="font-bold text-xl bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">AraMeGlobal</span>
        </div>

        <!-- Main Menu -->
        <div class="flex items-center space-x-1">
            <a href="{{ route('dashboard') }}" class="relative px-4 py-2 text-sm font-semibold text-blue-600 after:absolute after:bottom-0 after:left-4 after:right-4 after:h-0.5 after:bg-gradient-to-r after:from-blue-500 after:to-indigo-500">
                Dashboard
            </a>
            
            <a href="#" class="relative px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors before:absolute before:bottom-0 before:left-4 before:right-4 before:h-0.5 before:bg-gray-300 before:scale-x-0 before:transition-transform hover:before:scale-x-100">
                Timesheet
            </a>
            
            <!-- Leave Management Dropdown -->
            <div class="dropdown relative" onmouseenter="this.querySelector('.dropdown-menu').style.display='block'" onmouseleave="this.querySelector('.dropdown-menu').style.display='none'">
                <button class="group flex items-center space-x-1 px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    <span class="group-hover:text-blue-600 transition-colors">Leave Management</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="dropdown-menu absolute left-0 mt-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 hidden" style="display: none;">
                    <a href="{{ route('leaves.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Leaves</span>
                    </a>
                    <a href="{{ route('company-holidays.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <span>Company Holidays</span>
                    </a>
                </div>
            </div>

            <a href="#" class="relative px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors before:absolute before:bottom-0 before:left-4 before:right-4 before:h-0.5 before:bg-gray-300 before:scale-x-0 before:transition-transform hover:before:scale-x-100">
                Project Management
            </a>
            
            <a href="#" class="relative px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors before:absolute before:bottom-0 before:left-4 before:right-4 before:h-0.5 before:bg-gray-300 before:scale-x-0 before:transition-transform hover:before:scale-x-100">
                Team
            </a>
            
            <!-- User -->
            <a href="{{ route('users.index') }}" class="relative px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors before:absolute before:bottom-0 before:left-4 before:right-4 before:h-0.5 before:bg-gray-300 before:scale-x-0 before:transition-transform hover:before:scale-x-100">
                User
            </a>
            
            <!-- Master Dropdown -->
            <div class="dropdown relative" onmouseenter="this.querySelector('.dropdown-menu').style.display='block'" onmouseleave="this.querySelector('.dropdown-menu').style.display='none'">
                <button class="group flex items-center space-x-1 px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    <span class="group-hover:text-blue-600 transition-colors">Master</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="dropdown-menu absolute left-0 mt-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 hidden" style="display: none;">
                    <a href="{{ route('roles.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span>Roles</span>
                    </a>
                    <a href="{{ route('permissions.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        <span>Permissions</span>
                    </a>
                    <a href="{{ route('departments.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>Departments</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown relative">
            <button class="flex items-center space-x-3 pl-4 border-l border-gray-200 cursor-pointer" onmouseenter="this.parentElement.querySelector('.dropdown-menu').style.display='block'" onmouseleave="this.parentElement.querySelector('.dropdown-menu').style.display='none'">
                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <span class="text-white text-sm font-bold">{{ substr(auth()->user()?->name ?? 'JD', 0, 2) }}</span>
                </div>
                <div class="hidden lg:block text-left">
                    <p class="text-sm font-semibold text-gray-800">{{ auth()->user()?->name ?? 'John Doe' }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()?->email ?? 'Software Engineer' }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div class="dropdown-menu absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 hidden" style="display: none;">
                <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-800">{{ auth()->user()?->name ?? 'John Doe' }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()?->email ?? 'john@example.com' }}</p>
                </div>
                <a href="#" class="flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>My Profile</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <span>Change Password</span>
                </a>
                <div class="border-t border-gray-100 mt-2 pt-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center space-x-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

