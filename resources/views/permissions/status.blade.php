@extends('layouts.app')

@section('title', 'Change Permission Status')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex items-center space-x-4">
        <a href="{{ route('permissions.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Change Permission Status</h1>
            <p class="text-gray-500 mt-1">Toggle between Active and Inactive states</p>
        </div>
    </div>
</div>

<!-- Status Toggle Card -->
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Permission Info Header -->
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 p-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <div class="text-white">
                    <h2 class="text-xl font-bold">View Dashboard</h2>
                    <code class="text-purple-100 text-sm bg-white/10 px-2 py-0.5 rounded">dashboard.view</code>
                </div>
            </div>
        </div>
        
        <!-- Current Status -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Current Status</p>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            Active
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 mb-1">Group</p>
                    <p class="text-lg font-semibold text-gray-800">Dashboard</p>
                </div>
            </div>
        </div>
        
        <!-- Toggle Form -->
        <form action="#" method="POST" class="p-6">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-4">Select New Status</label>
                
                <div class="grid grid-cols-2 gap-4">
                    <!-- Active Option -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="status" value="active" class="peer sr-only">
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
                            <p class="font-semibold text-gray-800">Active</p>
                            <p class="text-sm text-gray-500 mt-1">Permission will be functional and assignable to roles</p>
                        </div>
                    </label>
                    
                    <!-- Inactive Option -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="status" value="inactive" class="peer sr-only">
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
                            <p class="font-semibold text-gray-800">Inactive</p>
                            <p class="text-sm text-gray-500 mt-1">Permission will be disabled and not usable</p>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Reason Input -->
            <div class="mb-6">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Change (Optional)</label>
                <textarea id="reason" name="reason" rows="2" placeholder="Enter reason for status change..." class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"></textarea>
            </div>
            
            <!-- Warning Message -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">Warning</p>
                        <p class="text-sm text-yellow-700 mt-1">If you set this permission to inactive, all roles with this permission will lose access to the associated functionality.</p>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('permissions.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

