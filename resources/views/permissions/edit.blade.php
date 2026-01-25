@extends('layouts.app')

@section('title', 'Edit Permission')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.permissions.index') }}" class="hover:text-blue-600">Permissions</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Edit Permission</li>
            </ol>
        </nav>

        <form action="#" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Permission Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Permission Name</label>
                <input type="text" id="name" name="name" value="View Dashboard" placeholder="e.g., View Dashboard" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                <p class="text-xs text-gray-500 mt-1">A descriptive name for the permission</p>
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
        </div>
        
        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.permissions.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                Update Permission
            </button>
        </div>
    </form>
    </div>
</div>
@endsection

