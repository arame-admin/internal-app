@extends('layouts.app')

@section('title', 'Apply Leave')

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-2xl mx-auto">
        <!-- Leave Balance Cards -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Leave Balance for {{ $year }}</h3>
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Sick Leave</p>
                            <p class="text-2xl font-bold text-red-600">{{ $leaveBalance['sick_leave_balance'] ?? 0 }}</p>
                            <p class="text-xs text-gray-400">of {{ $leaveBalance['sick_leave'] ?? 0 }} days</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Casual Leave</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $leaveBalance['casual_leave_balance'] ?? 0 }}</p>
                            <p class="text-xs text-gray-400">of {{ $leaveBalance['casual_leave'] ?? 0 }} days</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Earned Leave</p>
                            <p class="text-2xl font-bold text-green-600">{{ $leaveBalance['earned_leave_balance'] ?? 0 }}</p>
                            <p class="text-xs text-gray-400">of {{ $leaveBalance['earned_leave'] ?? 0 }} days</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">Apply for Leave</h2>
                <p class="text-sm text-gray-500 mt-1">Submit a new leave request</p>
            </div>
            
            <form action="{{ auth()->user()->role_id == 2 ? route('manager.leaves.store') : route('employee.leaves.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="space-y-6">
                    <!-- Year Selection -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                            Year <span class="text-red-500">*</span>
                        </label>
                        <select id="year" name="year" required
                            onchange="updateDatePickerYear()"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @for($y = date('Y'); $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Leave Type -->
                    <div>
                        <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Leave Type <span class="text-red-500">*</span>
                        </label>
                        <select id="leave_type" name="leave_type" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Select Leave Type</option>
                            <option value="sick_leave">Sick Leave</option>
                            <option value="casual_leave">Casual Leave</option>
                            <option value="earned_leave">Earned Leave</option>
                        </select>
                        @error('leave_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Leave Duration -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Leave Duration <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="duration_type" value="full_day" id="full_day" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium">Full Day</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="duration_type" value="half_day" id="half_day" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium">Half Day</span>
                            </label>
                        </div>
                        @error('duration_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Half Period (hidden by default) -->
                    <div id="half_period_container" style="display: none;">
                        <label for="half_period" class="block text-sm font-medium text-gray-700 mb-2">
                            Half Day Period <span class="text-red-500">*</span>
                        </label>
                        <select id="half_period" name="half_period" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Select Period</option>
                            <option value="first_half">First Half (Morning)</option>
                            <option value="second_half">Second Half (Afternoon)</option>
                        </select>
                        @error('half_period')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Date Range -->  

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="start_date" name="start_date" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Select start date" autocomplete="off">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="end_date" name="end_date" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Select end date" autocomplete="off">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Days Display -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-blue-700">Number of Days:</span>
                            <span id="total_days" class="text-2xl font-bold text-blue-700">0</span>
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

@push('scripts')
<script>
$(document).ready(function() {
    var selectedYear = {{ $year }};
    
    // Initialize datepickers with weekend disabled
    $('#start_date').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        minDate: new Date(selectedYear, 0, 1),
        maxDate: new Date(selectedYear, 11, 31),
        beforeShowDay: function(date) {
            var day = date.getDay();
            // 0 = Sunday, 6 = Saturday - disable weekends
            if (day === 0 || day === 6) {
                return [false, 'ui-datepicker-week-end', 'Weekend'];
            }
            return [true, '', ''];
        },
        onSelect: function(selectedDate) {
            updateDateRange();
            calculateDays();
        }
    });
    
    $('#end_date').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        minDate: new Date(selectedYear, 0, 1),
        maxDate: new Date(selectedYear, 11, 31),
        beforeShowDay: function(date) {
            var day = date.getDay();
            // 0 = Sunday, 6 = Saturday - disable weekends
            if (day === 0 || day === 6) {
                return [false, 'ui-datepicker-week-end', 'Weekend'];
            }
            return [true, '', ''];
        },
        onSelect: function() {
            calculateDays();
        }
    });
    
    // Calculate days excluding weekends or handle half day
    function calculateDays() {
        var durationType = $('input[name=\"duration_type\"]:checked').val();
        var startDate = $('#start_date').datepicker('getDate');
        var endDate = $('#end_date').datepicker('getDate');
        
        if (durationType === 'half_day') {
            $('#total_days').html('<span class=\"text-lg\">0.5</span><span class=\"text-sm ml-1\">Half Day</span>');
            return;
        }
        
        if (startDate && endDate) {
            var diffDays = 0;
            var currentDate = new Date(startDate);
            while (currentDate <= endDate) {
                var day = currentDate.getDay();
                if (day !== 0 && day !== 6) {
                    diffDays++;
                }
                currentDate.setDate(currentDate.getDate() + 1);
            }
            $('#total_days').text(diffDays);
        } else {
            $('#total_days').text('0');
        }
    }
    
    // Update date range when start date changes
    function updateDateRange() {
        var startDate = $('#start_date').datepicker('getDate');
        if (startDate) {
            var dateStr = $.datepicker.formatDate('yy-mm-dd', startDate);
            $('#end_date').datepicker('option', 'minDate', startDate);
            $('#end_date').val(dateStr); // Auto-fill end date with start date
            calculateDays();
        }
    }
    window.updateDateRange = updateDateRange;
    
// Duration type change handler
$('input[name="duration_type"]').change(function() {
    var durationType = $(this).val();
    var halfContainer = $('#half_period_container');
    var startDate = $('#start_date').datepicker('getDate');
    
    if (durationType === 'half_day') {
        halfContainer.show();
        // For half day, force single day
        if (startDate) {
            $('#end_date').datepicker('option', 'minDate', startDate);
            $('#end_date').datepicker('option', 'maxDate', startDate);
            $('#end_date').val(startDate ? startDate.toLocaleDateString('en-CA') : '');
        } else {
            $('#end_date').datepicker('option', 'minDate', null);
            $('#end_date').datepicker('option', 'maxDate', null);
        }
        $('#end_date').datepicker('option', 'minDate', new Date(selectedYear, 0, 1));
    } else {
        halfContainer.hide();
        $('#half_period').val('');
        // Allow multi-day for full day
        if (startDate) {
            $('#end_date').datepicker('option', 'minDate', startDate);
        }
        $('#end_date').datepicker('option', 'maxDate', new Date(selectedYear, 11, 31));
    }
    calculateDays();
});

// Expose function globally
window.calculateDays = calculateDays;

// Update datepicker year range when year changes
window.updateDatePickerYear = function() {
    var year = parseInt($('#year').val());
    selectedYear = year;
    $('#start_date, #end_date').datepicker('option', 'minDate', new Date(year, 0, 1));
    $('#start_date, #end_date').datepicker('option', 'maxDate', new Date(year, 11, 31));
    $('#start_date, #end_date').val('');
    $('#end_date').datepicker('option', 'minDate', new Date(year, 0, 1));
    $('#total_days').text('0');
    // Trigger duration check
    $('input[name="duration_type"]').trigger('change');
};
});
</script>
@endpush
@endsection
