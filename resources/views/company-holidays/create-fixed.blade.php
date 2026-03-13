@extends('layouts.app')

@section('title', 'Create Company Holiday')

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
                <li class="text-gray-900 font-medium">Create</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Create Company Holiday</h1>
            <p class="text-gray-600 mt-1">Add a new holiday year with mandatory and optional holidays.</p>
        </div>

        <!-- Global Flash Messages -->
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765 -1.36 2.722 -1.36 3.486 0l5.58 9.92c.75 1.334 -.213 2.98 -1.742 2.98H4.42c -1.53 0 -2.493 -1.646 -1.743 -2.98l5.58 -9.92zM11 13a1 1 0 11 -2 0 1 1 0 012 0zm -1 1a2 2 0 100 -4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Please correct the following errors:</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('company-holidays.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">Year <span class="text-red-500">*</span></label>
                        <select id="year" name="year"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white @error('year') border-red-300 @enderror">
                            @php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 2;
                                $endYear = $currentYear + 10;
                            @endphp
                            @for($year = $startYear; $year <= $endYear; $year++)
                                <option value="{{ $year }}" {{ old('year', request('year', $currentYear + 1)) == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Mandatory Holidays Table -->
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-semibold text-gray-700">Mandatory Holidays <span class="text-red-500">*</span></label>
                        <button type="button" id="add-mandatory-row" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Holiday
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="mandatory-table" class="w-full border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Holiday Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Holiday Name</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="mandatory-rows">
                                <!-- Default row -->
                                <tr class="holiday-row">
                                    <td class="px-4 py-3">
                                        <input type="date" name="mandatory_holidays[0][date]" class="date-input w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mandatory_holidays.0.date') border-red-300 @enderror" value="{{ old('mandatory_holidays.0.date') }}">
                                        @error('mandatory_holidays.0.date')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="mandatory_holidays[0][name]" value="{{ old('mandatory_holidays.0.name') }}" placeholder="Enter holiday name"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mandatory_holidays.0.name') border-red-300 @enderror">
                                        @error('mandatory_holidays.0.name')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" class="remove-row text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors p-2" title="Remove" disabled>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            <span id="mandatory-count">1</span> mandatory holiday(s)
                        </p>
                    </div>
                </div>

                <!-- Optional Holidays Table -->
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-semibold text-gray-700">Optional Holidays</label>
                        <button type="button" id="add-optional-row" class="px-4 py-2 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Holiday
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="optional-table" class="w-full border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Holiday Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Holiday Name</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="optional-rows">
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            <span id="optional-count">0</span> optional holiday(s)
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
                        Create Holiday Year
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Native JavaScript - No jQuery needed
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.getElementById('year');
    const mandatoryRows = document.getElementById('mandatory-rows');
    const optionalRows = document.getElementById('optional-rows');
    const mandatoryCount = document.getElementById('mandatory-count');
    const optionalCount = document.getElementById('optional-count');
    
    let mandatoryIndex = 1;
    let optionalIndex = 0;
    const disabledDates = @json($existingHolidayDates ?? []);
    let currentYear = parseInt(yearSelect.value);

    // Update date inputs min/max based on year
    function updateDateRanges() {
        currentYear = parseInt(yearSelect.value);
        const dateInputs = document.querySelectorAll('.date-input');
        dateInputs.forEach(input => {
            input.min = `${currentYear}-01-01`;
            input.max = `${currentYear}-12-31`;
        });
    }

    yearSelect.addEventListener('change', updateDateRanges);
    updateDateRanges(); // Initial

    // Mandatory row template
    const mandatoryTemplate = `
        <tr class="holiday-row">
            <td class="px-4 py-3">
                <input type="date" name="mandatory_holidays[%INDEX%][date]" class="date-input w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </td>
            <td class="px-4 py-3">
                <input type="text" name="mandatory_holidays[%INDEX%][name]" placeholder="Enter holiday name" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" class="remove-row p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </td>
        </tr>
    `;

    // Optional row template
    const optionalTemplate = `
        <tr class="optional-row">
            <td class="px-4 py-3">
                <input type="date" name="optional_holidays[%INDEX%][date]" class="date-input w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </td>
            <td class="px-4 py-3">
                <input type="text" name="optional_holidays[%INDEX%][name]" placeholder="Enter holiday name" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" class="remove-row p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </td>
        </tr>
    `;

    // Add mandatory row
    document.getElementById('add-mandatory-row').addEventListener('click', () => {
        const newRow = mandatoryTemplate.replace('%INDEX%', mandatoryIndex);
        mandatoryRows.insertAdjacentHTML('beforeend', newRow);
        mandatoryIndex++;
        updateCounts();
        toggleRemoveButtons('mandatory');
        updateDateRanges();
    });

    // Add optional row
    document.getElementById('add-optional-row').addEventListener('click', () => {
        const newRow = optionalTemplate.replace('%INDEX%', optionalIndex);
        optionalRows.insertAdjacentHTML('beforeend', newRow);
        optionalIndex++;
        updateCounts();
        toggleRemoveButtons('optional');
        updateDateRanges();
    });

    // Remove row handler (delegated)
    document.addEventListener('click', (e) => {
        if (e.target.closest('.remove-row')) {
            const row = e.target.closest('tr');
            const isMandatory = row.parentNode.id === 'mandatory-rows';
            
            if ((isMandatory && mandatoryRows.children.length > 1) || (!isMandatory && optionalRows.children.length > 0)) {
                row.remove();
                if (isMandatory) {
                    reindexRows(mandatoryRows);
                    mandatoryIndex--;
                } else {
                    reindexRows(optionalRows);
                    optionalIndex--;
                }
                updateCounts();
                toggleRemoveButtons(isMandatory ? 'mandatory' : 'optional');
            }
        }
    });

    function reindexRows(container) {
        Array.from(container.children).forEach((row, index) => {
            const dateInput = row.querySelector('input[type="date"]');
            const nameInput = row.querySelector('input[type="text"]');
            if (dateInput) dateInput.name = dateInput.name.replace(/\[\d+\]/, `[${index}]`);
            if (nameInput) nameInput.name = nameInput.name.replace(/\[\d+\]/, `[${index}]`);
        });
    }

    function updateCounts() {
        mandatoryCount.textContent = mandatoryRows.children.length;
        optionalCount.textContent = optionalRows.children.length;
    }

    function toggleRemoveButtons(type) {
        const container = type === 'mandatory' ? mandatoryRows : optionalRows;
        Array.from(container.children).forEach((row, index) => {
            const btn = row.querySelector('.remove-row');
            btn.disabled = (type === 'mandatory' && index === 0);
        });
    }

    // Initial setup
    updateCounts();
    toggleRemoveButtons('mandatory');
});
</script>
@endsection

