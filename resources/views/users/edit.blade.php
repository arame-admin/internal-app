@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('users.index') }}" class="hover:text-blue-600">Users</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Edit User</li>
            </ol>
        </nav>

        <form action="#" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Full Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" id="name" name="name" value="John Doe" placeholder="Enter full name" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" id="email" name="email" value="john@example.com" placeholder="Enter email address" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select id="role" name="role" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white" required>
                        <option value="">Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ $role == 'Administrator' ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <select id="department" name="department" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white" required>
                        <option value="">Select a department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department }}" {{ $department == 'IT' ? 'selected' : '' }}>{{ $department }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="text" id="phone" name="phone" value="+1 234-567-8901" placeholder="e.g., +1 234-567-8900" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
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
            <a href="{{ route('users.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                Update User
            </button>
        </div>
    </form>
    </div>
</div>
@endsection

