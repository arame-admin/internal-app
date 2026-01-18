@extends('layouts.app')

@section('title', 'Change Holiday Status')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="#" class="hover:text-blue-600">Leave Management</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('company-holidays.index') }}" class="hover:text-blue-600">Company Holidays</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Change Status</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Change Holiday Status</h1>
            <p class="text-gray-600 mt-1">Update the status for {{ $holiday['year'] }} holiday year.</p>
        </div>

        <!-- Current Status Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $holiday['year'] }} Holiday Year</h3>
                    <p class="text-gray-600 mt-1">
                        @php
                            $mandatoryCount = is_array($holiday['mandatory_holidays']) ? count($holiday['mandatory_holidays']) : $holiday['mandatory_holidays'];
                            $optionalCount = is_array($holiday['optional_holidays']) ? count($holiday['optional_holidays']) : $holiday['optional_holidays'];
                            $totalCount = $mandatoryCount + $optionalCount;
                        @endphp
                        {{ $mandatoryCount }} mandatory + {{ $optionalCount }} optional = {{ $totalCount }} total holidays
                    </p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $holiday['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($holiday['status']) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Status Change Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('company-holidays.update', $holiday['id']) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">New Status</label>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="active" {{ $holiday['status'] == 'active' ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-3 text-sm">
                                <span class="font-medium text-gray-900">Active</span>
                                <span class="text-gray-500 block">Holiday year is active and visible to employees</span>
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="inactive" {{ $holiday['status'] == 'inactive' ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-3 text-sm">
                                <span class="font-medium text-gray-900">Inactive</span>
                                <span class="text-gray-500 block">Holiday year is inactive and hidden from employees</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('company-holidays.index') }}"
                       class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection