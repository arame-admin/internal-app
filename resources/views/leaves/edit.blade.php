@extends('layouts.app')

@section('title', 'Edit Leave Configuration')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.leaves.index') }}" class="hover:text-blue-600">Leaves</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Edit Leave Configuration</li>
            </ol>
        </nav>

        <!-- Leave Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.leaves.update', $leave['id']) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                        <select id="year" name="year" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                            <option value="">Select Year</option>
                            @for ($y = date('Y'); $y <= date('Y') + 5; $y++)
                                <option value="{{ $y }}" {{ $leave['year'] == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <p class="text-xs text-gray-500 mt-1">The year for which leave configuration applies</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                            <option value="active" {{ $leave['status'] == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $leave['status'] == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Sick Leaves -->
                    <div>
                        <label for="sick_leaves" class="block text-sm font-medium text-gray-700 mb-2">Sick Leaves</label>
                        <input type="number" id="sick_leaves" name="sick_leaves" value="{{ $leave['sick_leaves'] }}" min="0" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                        <p class="text-xs text-gray-500 mt-1">Number of sick leaves per year</p>
                    </div>

                    <!-- Casual Leaves -->
                    <div>
                        <label for="casual_leaves" class="block text-sm font-medium text-gray-700 mb-2">Casual Leaves</label>
                        <input type="number" id="casual_leaves" name="casual_leaves" value="{{ $leave['casual_leaves'] }}" min="0" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                        <p class="text-xs text-gray-500 mt-1">Number of casual leaves per year</p>
                    </div>

                    <!-- Earned Leaves -->
                    <div>
                        <label for="earned_leaves" class="block text-sm font-medium text-gray-700 mb-2">Earned Leaves</label>
                        <input type="number" id="earned_leaves" name="earned_leaves" value="{{ $leave['earned_leaves'] }}" min="0" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                        <p class="text-xs text-gray-500 mt-1">Number of earned leaves per year</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.leaves.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                        Update Leave Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection