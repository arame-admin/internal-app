@extends('layouts.app')

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">Apply for Leave</h2>
                <p class="text-sm text-gray-500 mt-1">Submit a new leave request</p>
            </div>
            
            <form action="{{ auth()->user()->role_id == 2 ? route('manager.leaves.store') : route('employee.leaves.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="space-y-6">
                    <!-- Leave Type -->
                    <div>
                        <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Leave Type <span class="text-red-500">*</span>
                        </label>
                        <select id="leave_type" name="leave_type" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Select Leave Type</option>
                            <option value="sick">Sick Leave</option>
                            <option value="casual">Casual Leave</option>
                            <option value="earned">Earned Leave</option>
                        </select>
                        @error('leave_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Date Range -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="start_date" name="start_date" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}enderror</p>
                            @
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="end_date" name="end_date" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Reason -->
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea id="reason" name="reason" rows="4" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Enter reason for leave..."></textarea>
                        @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-3">
                    <a href="{{ url()->previous() }}" 
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        Submit Leave Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
