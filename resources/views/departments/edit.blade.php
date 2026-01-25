{{-- Department Edit Form --}}
{{-- This page provides a form to edit an existing department with validation and error display. --}}
{{-- Includes server-side validation for name and code uniqueness, excluding the current record. --}}

@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.departments.index') }}" class="hover:text-blue-600">Departments</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Edit Department</li>
            </ol>
        </nav>

        <form action="{{ route('admin.departments.update', Crypt::encrypt($department->id)) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Department Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Department Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $department->name) }}" placeholder="e.g., Information Technology" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                @error('name')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
                <p class="text-xs text-gray-500 mt-1">A descriptive name for the department</p>
            </div>

            <!-- Department Code -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Department Code</label>
                <input type="text" id="code" name="code" value="{{ old('code', $department->code) }}" placeholder="e.g., IT" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                @error('code')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Unique code identifier (cannot be changed)</p>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Optional description of the department..." class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">{{ $department->description }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Optional description (max 500 characters)</p>
            </div>

        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.departments.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                Update Department
            </button>
        </div>
    </form>
    </div>
</div>
@endsection