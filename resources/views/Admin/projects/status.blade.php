@extends('layouts.app')

@section('title', 'Change Project Status')

@section('content')
<!-- Status Toggle Card -->
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Project Info Header -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="text-white">
                    <h2 class="text-xl font-bold">E-Commerce Platform</h2>
                    <p class="text-purple-100 text-sm">John Doe</p>
                </div>
            </div>
        </div>

        <!-- Current Status -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Current Status</p>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            In Progress
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 mb-1">Progress</p>
                    <p class="text-lg font-semibold text-gray-800">65%</p>
                </div>
            </div>
        </div>

        <!-- Toggle Form -->
        <form action="#" method="POST" class="p-6">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-4">Select New Status</label>

                <div class="grid grid-cols-1 gap-4">
                    <!-- Planning Option -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="status" value="planning" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 transition-all group-hover:border-yellow-300">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-yellow-500 peer-checked:bg-yellow-500 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-800">Planning</p>
                            <p class="text-sm text-gray-500 mt-1">Project is in planning phase</p>
                        </div>
                    </label>

                    <!-- In Progress Option -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="status" value="in_progress" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all group-hover:border-blue-300">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-800">In Progress</p>
                            <p class="text-sm text-gray-500 mt-1">Project is actively being developed</p>
                        </div>
                    </label>

                    <!-- On Hold Option -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="status" value="on_hold" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all group-hover:border-orange-300">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-orange-500 peer-checked:bg-orange-500 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-800">On Hold</p>
                            <p class="text-sm text-gray-500 mt-1">Project development is temporarily paused</p>
                        </div>
                    </label>

                    <!-- Testing Option -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="status" value="testing" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all group-hover:border-purple-300">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-purple-500 peer-checked:bg-purple-500 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-800">Testing</p>
                            <p class="text-sm text-gray-500 mt-1">Project is in testing and QA phase</p>
                        </div>
                    </label>

                    <!-- Completed Option -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="status" value="completed" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all group-hover:border-green-300">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-800">Completed</p>
                            <p class="text-sm text-gray-500 mt-1">Project has been successfully delivered</p>
                        </div>
                    </label>

                    <!-- Cancelled Option -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="status" value="cancelled" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all group-hover:border-red-300">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-red-500 peer-checked:bg-red-500 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-800">Cancelled</p>
                            <p class="text-sm text-gray-500 mt-1">Project has been cancelled</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Reason Input -->
            <div class="mb-6">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Change (Optional)</label>
                <textarea id="reason" name="reason" rows="2" placeholder="Enter reason for status change..." class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none"></textarea>
            </div>

            <!-- Warning Message -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">Warning</p>
                        <p class="text-sm text-yellow-700 mt-1">Changing the project status will affect project tracking and reporting. Make sure all team members are notified.</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.projects.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-colors font-medium shadow-lg shadow-purple-500/30">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection