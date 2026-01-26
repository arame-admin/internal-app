{{-- Designation Edit Form --}}
{{-- This page provides a form to edit an existing designation with validation and error display. --}}
{{-- Includes server-side validation for name and code uniqueness, excluding the current record. --}}

@extends('layouts.app')

@section('title', 'Edit Designation')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.designations.index') }}" class="hover:text-blue-600">Designations</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Edit Designation</li>
            </ol>
        </nav>

        <form action="{{ route('admin.designations.update', Crypt::encrypt($designation->id)) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Designation Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Designation Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $designation->name) }}" placeholder="e.g., Software Engineer" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                @error('name')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
                <p class="text-xs text-gray-500 mt-1">A descriptive name for the designation</p>
            </div>

            <!-- Designation Code -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Designation Code</label>
                <input type="text" id="code" name="code" value="{{ old('code', $designation->code) }}" placeholder="e.g., SE" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                @error('code')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Unique code identifier (cannot be changed)</p>
            </div>

            <!-- Department -->
            <div>
                <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select id="department_id" name="department_id" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ (old('department_id', $designation->department_id)) == $department->id ? 'selected' : '' }}>
                            {{ $department->name }} ({{ $department->code }})
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Select the department this designation belongs to</p>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Optional description of the designation..." class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">{{ $designation->description }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Optional description (max 500 characters)</p>
            </div>

        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.designations.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                Update Designation
            </button>
        </div>
    </form>
    </div>
</div>
@endsection