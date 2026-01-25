@extends('layouts.app')

@section('title', 'Edit Company Holiday')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="#" class="hover:text-blue-600">Leave Management</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('company-holidays.index') }}" class="hover:text-blue-600">Company Holidays</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Edit</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Edit Company Holiday</h1>
            <p class="text-gray-600 mt-1">Update holiday information for {{ $holiday['year'] }}.</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('company-holidays.update', $holiday['id']) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">Year <span class="text-red-500">*</span></label>
                        <input type="number" id="year" name="year" value="{{ old('year', $holiday['year']) }}" min="2020" max="2030"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('year') border-red-300 @enderror"
                               placeholder="Select year">
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Mandatory Holidays Table -->
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-semibold text-gray-700">Mandatory Holidays <span class="text-red-500">*</span></label>
                        <button type="button" id="add-holiday-row" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Holiday
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="mandatory-holidays-table" class="w-full border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Holiday Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Holiday Name</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="holiday-rows">
                                @php
                                    $mandatoryHolidays = old('mandatory_holidays', $holiday['mandatory_holidays'] ?? []);
                                    if (empty($mandatoryHolidays)) {
                                        $mandatoryHolidays = [['date' => '', 'name' => '']];
                                    }
                                @endphp
                                @foreach($mandatoryHolidays as $index => $holidayData)
                                <tr class="holiday-row">
                                    <td class="px-4 py-3">
                                        <input type="date" name="mandatory_holidays[{{ $index }}][date]" value="{{ $holidayData['date'] ?? '' }}"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mandatory_holidays.' . $index . '.date') border-red-300 @enderror">
                                        @error('mandatory_holidays.' . $index . '.date')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="mandatory_holidays[{{ $index }}][name]" value="{{ $holidayData['name'] ?? '' }}" placeholder="Enter holiday name"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mandatory_holidays.' . $index . '.name') border-red-300 @enderror">
                                        @error('mandatory_holidays.' . $index . '.name')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" class="remove-holiday-row p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            <span id="holiday-count">{{ count($mandatoryHolidays) }}</span> mandatory holiday(s) added.
                        </p>
                    </div>
                </div>

                <!-- Optional Holidays Table -->
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-semibold text-gray-700">Optional Holidays</label>
                        <button type="button" id="add-optional-holiday-row" class="px-4 py-2 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Holiday
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="optional-holidays-table" class="w-full border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Holiday Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Holiday Name</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="optional-holiday-rows">
                                @php
                                    $optionalHolidays = old('optional_holidays', $holiday['optional_holidays'] ?? []);
                                @endphp
                                @foreach($optionalHolidays as $index => $holidayData)
                                <tr class="optional-holiday-row">
                                    <td class="px-4 py-3">
                                        <input type="date" name="optional_holidays[{{ $index }}][date]" value="{{ $holidayData['date'] ?? '' }}"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('optional_holidays.' . $index . '.date') border-red-300 @enderror">
                                        @error('optional_holidays.' . $index . '.date')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="optional_holidays[{{ $index }}][name]" value="{{ $holidayData['name'] ?? '' }}" placeholder="Enter holiday name"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('optional_holidays.' . $index . '.name') border-red-300 @enderror">
                                        @error('optional_holidays.' . $index . '.name')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" class="remove-optional-holiday-row p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            <span id="optional-holiday-count">{{ count($optionalHolidays) }}</span> optional holiday(s) added.
                        </p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('company-holidays.index') }}"
                       class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors">
                        Update Holiday Year
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mandatory Holidays
    const addMandatoryHolidayBtn = document.getElementById('add-holiday-row');
    const mandatoryHolidayRowsContainer = document.getElementById('holiday-rows');
    const mandatoryHolidayCountDisplay = document.getElementById('holiday-count');

    let mandatoryRowIndex = document.querySelectorAll('.holiday-row').length; // Start from current count

    // Template for new mandatory holiday row
    const mandatoryHolidayRowTemplate = `
        <tr class="holiday-row">
            <td class="px-4 py-3">
                <input type="date" name="mandatory_holidays[INDEX][date]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </td>
            <td class="px-4 py-3">
                <input type="text" name="mandatory_holidays[INDEX][name]" placeholder="Enter holiday name" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" class="remove-holiday-row p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </td>
        </tr>
    `;

    // Optional Holidays
    const addOptionalHolidayBtn = document.getElementById('add-optional-holiday-row');
    const optionalHolidayRowsContainer = document.getElementById('optional-holiday-rows');
    const optionalHolidayCountDisplay = document.getElementById('optional-holiday-count');

    let optionalRowIndex = document.querySelectorAll('.optional-holiday-row').length; // Start from current count

    // Template for new optional holiday row
    const optionalHolidayRowTemplate = `
        <tr class="optional-holiday-row">
            <td class="px-4 py-3">
                <input type="date" name="optional_holidays[INDEX][date]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </td>
            <td class="px-4 py-3">
                <input type="text" name="optional_holidays[INDEX][name]" placeholder="Enter holiday name" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" class="remove-optional-holiday-row p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </td>
        </tr>
    `;

    // Add mandatory holiday row
    addMandatoryHolidayBtn.addEventListener('click', function() {
        const newRow = mandatoryHolidayRowTemplate.replace(/INDEX/g, mandatoryRowIndex);
        mandatoryHolidayRowsContainer.insertAdjacentHTML('beforeend', newRow);
        mandatoryRowIndex++;
        updateMandatoryHolidayCount();
    });

    // Add optional holiday row
    addOptionalHolidayBtn.addEventListener('click', function() {
        const newRow = optionalHolidayRowTemplate.replace(/INDEX/g, optionalRowIndex);
        optionalHolidayRowsContainer.insertAdjacentHTML('beforeend', newRow);
        optionalRowIndex++;
        updateOptionalHolidayCount();
    });

    // Remove holiday rows
    document.addEventListener('click', function(e) {
        // Remove mandatory holiday row
        if (e.target.closest('.remove-holiday-row')) {
            const row = e.target.closest('.holiday-row');
            const rows = document.querySelectorAll('.holiday-row');

            // Don't remove if it's the last row
            if (rows.length > 1) {
                row.remove();
                updateMandatoryHolidayCount();
                reindexMandatoryRows();
            }
        }

        // Remove optional holiday row
        if (e.target.closest('.remove-optional-holiday-row')) {
            const row = e.target.closest('.optional-holiday-row');
            row.remove();
            updateOptionalHolidayCount();
            reindexOptionalRows();
        }
    });

    // Update holiday count displays
    function updateMandatoryHolidayCount() {
        const rowCount = document.querySelectorAll('.holiday-row').length;
        mandatoryHolidayCountDisplay.textContent = rowCount;
    }

    function updateOptionalHolidayCount() {
        const rowCount = document.querySelectorAll('.optional-holiday-row').length;
        optionalHolidayCountDisplay.textContent = rowCount;
    }

    // Reindex array indices after row removal
    function reindexMandatoryRows() {
        const rows = document.querySelectorAll('.holiday-row');
        rows.forEach((row, index) => {
            const dateInput = row.querySelector('input[type="date"]');
            const nameInput = row.querySelector('input[type="text"]');

            if (dateInput) dateInput.name = `mandatory_holidays[${index}][date]`;
            if (nameInput) nameInput.name = `mandatory_holidays[${index}][name]`;
        });
        mandatoryRowIndex = rows.length;
    }

    function reindexOptionalRows() {
        const rows = document.querySelectorAll('.optional-holiday-row');
        rows.forEach((row, index) => {
            const dateInput = row.querySelector('input[type="date"]');
            const nameInput = row.querySelector('input[type="text"]');

            if (dateInput) dateInput.name = `optional_holidays[${index}][date]`;
            if (nameInput) nameInput.name = `optional_holidays[${index}][name]`;
        });
        optionalRowIndex = rows.length;
    }

    // Initial count updates
    updateMandatoryHolidayCount();
    updateOptionalHolidayCount();
});
</script>
@endsection