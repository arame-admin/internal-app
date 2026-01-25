@extends('layouts.app')

@section('title', 'Change Leave Status')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.leaves.index') }}" class="hover:text-blue-600">Leaves</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Change Status</li>
            </ol>
        </nav>

        <!-- Status Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Change Leave Configuration Status</h2>
            <p class="text-gray-600 mb-6">Update the status for leave configuration of year {{ $leave['year'] }}.</p>

            <form action="{{ route('admin.leaves.update', $leave['id']) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Status</label>
                    <div class="flex items-center space-x-2">
                        @if($leave['status'] == 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Inactive
                            </span>
                        @endif>
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                    <select id="status" name="status" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                        <option value="active" {{ $leave['status'] == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $leave['status'] == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.leaves.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection