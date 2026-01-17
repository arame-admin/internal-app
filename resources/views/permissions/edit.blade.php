@extends('layouts.app')

@section('title', 'Edit Permission')

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
            <h1 class="text-3xl font-bold text-gray-800">Edit Permission</h1>
            <p class="text-gray-500 mt-1">Modify permission details</p>
        </div>
    </div>
</div>

<div class="max-w-3xl">
    <form action="#" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Permission Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Permission Name</label>
                <input type="text" id="name" name="name" value="View Dashboard" placeholder="e.g., View Dashboard" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                <p class="text-xs text-gray-500 mt-1">A descriptive name for the permission</p>
            </div>
            
            <!-- Slug -->
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                <input type="text" id="slug" name="slug" value="dashboard.view" placeholder="e.g., dashboard.view" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-gray-50" readonly>
                <p class="text-xs text-gray-500 mt-1">Unique identifier (cannot be changed)</p>
            </div>
            
            <!-- Group -->
            <div>
                <label for="group" class="block text-sm font-medium text-gray-700 mb-2">Permission Group</label>
                <select id="group" name="group" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white" required>
                    <option value="">Select a group</option>
                    @foreach($groups as $group)
                        <option value="{{ $group }}" {{ $group == 'Dashboard' ? 'selected' : '' }}>{{ $group }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Group permissions by module or feature</p>
            </div>
            
            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Describe what this permission allows" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none">Access to view dashboard and its widgets</textarea>
            </div>
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="status" value="active" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="status" value="inactive" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Inactive</span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('permissions.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                Update Permission
            </button>
        </div>
    </form>
</div>
@endsection

