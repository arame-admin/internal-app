<!-- Top Navigation Bar -->
<nav class="fixed top-0 left-0 right-0 h-16 bg-white border-b border-gray-200 shadow-sm z-50">
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
            @php
                $userRole = auth()->user()?->role_id ?? 0;
                $user = auth()->user();
                $hasSubordinates = $user && $user->subordinates()->count() > 0;
                $isManager = $userRole == 2 || $hasSubordinates;
            @endphp
            
            {{-- Dashboard (visible to all) --}}
            @switch($userRole)
                @case(1)
                    <a href="{{ route('admin.dashboard') }}" class="relative px-4 py-2 text-sm font-semibold text-blue-600 after:absolute after:bottom-0 after:left-4 after:right-4 after:h-0.5 after:bg-gradient-to-r after:from-blue-500 after:to-indigo-500">
                        Dashboard
                    </a>
                    @break
                @case(2)
                    <a href="{{ route('manager.dashboard') }}" class="relative px-4 py-2 text-sm font-semibold text-blue-600 after:absolute after:bottom-0 after:left-4 after:right-4 after:h-0.5 after:bg-gradient-to-r after:from-blue-500 after:to-indigo-500">
                        Dashboard
                    </a>
                    @break
                @case(3)
                    @if($hasSubordinates)
                    <a href="{{ route('manager.dashboard') }}" class="relative px-4 py-2 text-sm font-semibold text-blue-600 after:absolute after:bottom-0 after:left-4 after:right-4 after:h-0.5 after:bg-gradient-to-r after:from-blue-500 after:to-indigo-500">
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('employee.dashboard') }}" class="relative px-4 py-2 text-sm font-semibold text-blue-600 after:absolute after:bottom-0 after:left-4 after:right-4 after:h-0.5 after:bg-gradient-to-r after:from-blue-500 after:to-indigo-500">
                        Dashboard
                    </a>
                    @endif
                    @break
                @default
                    <a href="#" class="relative px-4 py-2 text-sm font-semibold text-blue-600 after:absolute after:bottom-0 after:left-4 after:right-4 after:h-0.5 after:bg-gradient-to-r after:from-blue-500 after:to-indigo-500">
                        Dashboard
                    </a>
            @endswitch
            
            {{-- Timesheet (visible to all) --}}
            @if($userRole == 1)
                <a href="{{ route('admin.timesheets.index') }}" class="relative px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors before:absolute before:bottom-0 before:left-4 before:right-4 before:h-0.5 before:bg-gray-300 before:scale-x-0 before:transition-transform hover:before:scale-x-100">
                    Timesheet
                </a>
            @elseif($isManager)
                <!-- Timesheet Dropdown for Managers -->
                <div class="dropdown relative" onmouseenter="this.querySelector('.dropdown-menu').style.display='block'" onmouseleave="this.querySelector('.dropdown-menu').style.display='none'">
                    <button class="group flex items-center space-x-1 px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                        <span class="group-hover:text-blue-600 transition-colors">Timesheet</span>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="dropdown-menu absolute left-0 mt-0 w-52 bg-white rounded-xl shadow-xl border border-gray-100 py-2 hidden" style="display: none;">
                        <a href="{{ route('manager.timesheets.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 hover:text-gray-900 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span>My Timesheets</span>
                        </a>
                        <a href="{{ route('manager.timesheets.apply') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Log Hours</span>
                        </a>
                        <a href="{{ route('manager.timesheets.team') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 hover:text-purple-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span>Team Timesheets</span>
                        </a>
                        <a href="{{ route('manager.timesheets.approve') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Approve Timesheets</span>
                        </a>
                    </div>
                </div>
            @else
                <!-- Timesheet Dropdown for Employees -->
                <div class="dropdown relative" onmouseenter="this.querySelector('.dropdown-menu').style.display='block'" onmouseleave="this.querySelector('.dropdown-menu').style.display='none'">
                    <button class="group flex items-center space-x-1 px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                        <span class="group-hover:text-blue-600 transition-colors">Timesheet</span>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="dropdown-menu absolute left-0 mt-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 hidden" style="display: none;">
                        <a href="{{ route('employee.timesheets.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <span>My Timesheets</span>
                        </a>
                        <a href="{{ route('employee.timesheets.apply') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Log Hours</span>
                        </a>
                    </div>
                </div>
            @endif
            
            {{-- Leave (role-specific) --}}
            @if($userRole == 1)
                <!-- Leave Dropdown -->
                <div class="dropdown relative" onmouseenter="this.querySelector('.dropdown-menu').style.display='block'" onmouseleave="this.querySelector('.dropdown-menu').style.display='none'">
                    <button class="group flex items-center space-x-1 px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                        <span class="group-hover:text-blue-600 transition-colors">Leave</span>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="dropdown-menu absolute left-0 mt-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 hidden" style="display: none;">
                        <a href="{{ route('admin.leaves.applications') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <span>Leave Applications</span>
                        </a>
                        <a href="{{ route('company-holidays.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Company Holidays</span>
                        </a>
                    </div>
                </div>
            @elseif($isManager)
            <!-- Leave Dropdown for Managers -->
            <div class="dropdown relative" onmouseenter="this.querySelector('.dropdown-menu').style.display='block'" onmouseleave="this.querySelector('.dropdown-menu').style.display='none'">
                <button class="group flex items-center space-x-1 px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    <span class="group-hover:text-blue-600 transition-colors">Leave</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="dropdown-menu absolute left-0 mt-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 hidden" style="display: none;">
                    <a href="{{ route('employee.leaves.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>My Leaves</span>
                    </a>
                    <a href="{{ route('manager.leaves.approve') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Approve Leaves</span>
                    </a>
                </div>
            </div>
            @else
            <a href="{{ route('employee.leaves.index') }}" class="relative px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors before:absolute before:bottom-0 before:left-4 before:right-4 before:h-0.5 before:bg-gray-300 before:scale-x-0 before:transition-transform hover:before:scale-x-100">
                Leave
            </a>
            @endif
            
            {{-- Admin & Manager specific menus --}}
            @if($userRole == 1 || $isManager)
                <!-- Project Management Dropdown -->
                <div class="dropdown relative" onmouseenter="this.querySelector('.dropdown-menu').style.display='block'" onmouseleave="this.querySelector('.dropdown-menu').style.display='none'">
                    <button class="group flex items-center space-x-1 px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                        <span class="group-hover:text-blue-600 transition-colors">Project Management</span>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="dropdown-menu absolute left-0 mt-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 hidden" style="display: none;">
                        @if($userRole == 1)
                            <a href="{{ route('admin.clients.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Clients</span>
                            </a>
                            <a href="{{ route('admin.projects.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Projects</span>
                            </a>
                        @endif
                        @if($userRole == 2)
                            <a href="#" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span>Team Projects</span>
                            </a>
                            <a href="#" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>My Team</span>
                            </a>
                        @endif
                    </div>
                </div>
                
                <a href="#" class="relative px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors before:absolute before:bottom-0 before:left-4 before:right-4 before:h-0.5 before:bg-gray-300 before:scale-x-0 before:transition-transform hover:before:scale-x-100">
                    Team
                </a>
            @endif
            
            {{-- Admin only menus --}}
            @if($userRole == 1)
                <!-- User -->
                <a href="{{ route('admin.users.index') }}" class="relative px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors before:absolute before:bottom-0 before:left-4 before:right-4 before:h-0.5 before:bg-gray-300 before:scale-x-0 before:transition-transform hover:before:scale-x-100">
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
                        <a href="{{ route('admin.roles.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span>Roles</span>
                        </a>
                        <a href="{{ route('admin.permissions.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            <span>Permissions</span>
                        </a>
                        <a href="{{ route('admin.departments.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>Departments</span>
                        </a>
                        <a href="{{ route('admin.designations.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span>Designations</span>
                        </a>
                        <a href="{{ route('admin.business-units.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>Business Units</span>
                        </a>
                        <a href="{{ route('admin.locations.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Locations</span>
                        </a>
                        <a href="{{ route('company-holidays.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Company Holidays</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- User Profile Dropdown -->
        <div class="flex items-center space-x-4">
            <!-- Timesheet Reminder Notification Bell -->
            @php
                $reminderCount = 0;
                $activeReminders = collect();
                if (auth()->check() && auth()->user()->role_id == 3) {
                    $activeReminders = \App\Models\TimesheetReminder::getActiveRemindersForUser(auth()->id());
                    $reminderCount = $activeReminders->count();
                }
            @endphp
            @if($reminderCount > 0)
            <div class="relative" onmouseenter="this.querySelector('.notification-dropdown').style.display='block'" onmouseleave="this.querySelector('.notification-dropdown').style.display='none'">
                <button class="relative p-2 text-amber-600 hover:text-amber-700 transition-colors cursor-pointer" title="Missing Timesheet Reminder">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-500 rounded-full">
                        {{ $reminderCount }}
                    </span>
                </button>
                <!-- Notification Dropdown -->
                <div class="notification-dropdown absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 hidden">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">Timesheet Reminder{{ $reminderCount > 1 ? 's' : '' }}</h3>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        @foreach($activeReminders as $reminder)
                        <a href="{{ route('employee.timesheets.apply') }}" class="flex items-start space-x-3 px-4 py-3 hover:bg-amber-50 transition-colors border-b border-gray-50 last:border-0">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Missing Timesheet</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reminder->missed_date)->format('M d, Y') }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-amber-700 bg-amber-100 rounded">
                                    Log Now
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    <div class="px-4 py-2 border-t border-gray-100">
                        <a href="{{ route('employee.timesheets.apply') }}" class="block text-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                            View All & Log Timesheet
                        </a>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="dropdown relative pt-2 pb-2" onmouseenter="this.querySelector('.dropdown-menu').style.display='block'" onmouseleave="this.querySelector('.dropdown-menu').style.display='none'">
                <button class="flex items-center space-x-3 pl-4 border-l border-gray-200 cursor-pointer">
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
            <div class="dropdown-menu absolute right-0 mt-0 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 hidden">
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
    </div>
</nav>

