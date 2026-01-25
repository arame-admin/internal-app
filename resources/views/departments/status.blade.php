@extends('layouts.app')

@section('title', 'Change Department Status')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen py-8">
    <div class="max-w-md mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.departments.index') }}" class="hover:text-blue-600">Departments</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Change Status</li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Change Department Status</h2>
                <p class="text-gray-600 text-sm mt-1">Update the status of this department</p>
            </div>

            <form action="{{ route('admin.departments.updateStatus', $id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">New Status</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="status" value="active" class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500" checked>
                                <span class="ml-3 text-sm text-gray-700">Active</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="status" value="inactive" class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                                <span class="ml-3 text-sm text-gray-700">Inactive</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason (Optional)</label>
                        <textarea id="reason" name="reason" rows="3" placeholder="Provide a reason for this status change..." class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.departments.index') }}" class="px-4 py-2 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection