@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
<!-- Header -->
<header class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Manager Dashboard</h1>
    <p class="text-gray-500 mt-1">Welcome back, {{ auth()->user()?->name }}</p>
</header>

<!-- Team Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Team Members</p>
                <p class="text-2xl font-bold text-gray-800">12</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
        <p class="text-sm text-green-600 mt-2">Active this week</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pending Approvals</p>
                <p class="text-2xl font-bold text-gray-800">5</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Leave requests</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Active Projects</p>
                <p class="text-2xl font-bold text-gray-800">8</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">3 due this week</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Team Hours</p>
                <p class="text-2xl font-bold text-gray-800">320</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <p class="text-sm text-green-600 mt-2">This week</p>
    </div>
</div>

<!-- Pending Leave Requests -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Pending Leave Requests</h2>
        <a href="#" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500 border-b border-gray-100">
                        <th class="pb-3 font-medium">Employee</th>
                        <th class="pb-3 font-medium">Type</th>
                        <th class="pb-3 font-medium">Duration</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr class="border-b border-gray-50">
                        <td class="py-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-xs">JD</span>
                                </div>
                                <span class="font-medium text-gray-800">John Doe</span>
                            </div>
                        </td>
                        <td class="py-3 text-gray-600">Annual Leave</td>
                        <td class="py-3 text-gray-600">Mar 20 - Mar 22</td>
                        <td class="py-3"><span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Pending</span></td>
                        <td class="py-3">
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200">Approve</button>
                                <button class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">Reject</button>
                            </div>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-50">
                        <td class="py-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <span class="text-purple-600 font-bold text-xs">AS</span>
                                </div>
                                <span class="font-medium text-gray-800">Alice Smith</span>
                            </div>
                        </td>
                        <td class="py-3 text-gray-600">Sick Leave</td>
                        <td class="py-3 text-gray-600">Mar 18 - Mar 18</td>
                        <td class="py-3"><span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Pending</span></td>
                        <td class="py-3">
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200">Approve</button>
                                <button class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">Reject</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Team Performance & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Team Performance -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Team Performance</h2>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <div>
                    <p class="font-medium text-gray-800">John Doe</p>
                    <p class="text-sm text-gray-500">Frontend Developer</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-green-600">95%</p>
                    <p class="text-sm text-gray-500">40 hrs/week</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <div>
                    <p class="font-medium text-gray-800">Alice Smith</p>
                    <p class="text-sm text-gray-500">Backend Developer</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-green-600">88%</p>
                    <p class="text-sm text-gray-500">38 hrs/week</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <div>
                    <p class="font-medium text-gray-800">Bob Johnson</p>
                    <p class="text-sm text-gray-500">Designer</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-yellow-600">82%</p>
                    <p class="text-sm text-gray-500">35 hrs/week</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Quick Actions</h2>
        </div>
        <div class="p-6 grid grid-cols-2 gap-4">
            <button class="flex flex-col items-center justify-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-colors border border-blue-100">
                <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700">Approve Leaves</span>
            </button>
            
            <button class="flex flex-col items-center justify-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl hover:from-purple-100 hover:to-pink-100 transition-colors border border-purple-100">
                <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700">Team View</span>
            </button>
            
            <button class="flex flex-col items-center justify-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-colors border border-green-100">
                <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700">Reports</span>
            </button>
            
            <button class="flex flex-col items-center justify-center p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl hover:from-orange-100 hover:to-amber-100 transition-colors border border-orange-100">
                <svg class="w-8 h-8 text-orange-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700">Schedule</span>
            </button>
        </div>
    </div>
</div>
@endsection

