@extends('layouts.app')

@section('title', 'Company Holidays Management')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="#" class="hover:text-blue-600">Leave Management</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Company Holidays</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Company Holidays Management</h1>
        </div>

        @php
            $selectedYear = request('year', date('Y'));
            $holidayData = collect($companyHolidays)->firstWhere('year', (int)$selectedYear);

            $months = [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ];
        @endphp

        <!-- Floating Add Button -->
        <a href="{{ route('company-holidays.create') }}" class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-full p-4 shadow-lg hover:from-blue-600 hover:to-indigo-700 transition-colors z-50" title="Add New Holiday Year">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </a>

        <!-- Year Filter & Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <label for="year" class="text-sm font-semibold text-gray-700">Select Year:</label>
                        <select name="year" id="year" onchange="this.form.submit()"
                                class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white">
                            @php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 2;
                                $endYear = $currentYear + 5;
                            @endphp
                            @for($year = $startYear; $year <= $endYear; $year++)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                        <form action="{{ route('company-holidays.index') }}" method="GET" class="hidden">
                            <input type="hidden" name="year" value="{{ $selectedYear }}">
                        </form>
                    </div>

                    <div class="flex items-center space-x-6">
                        @if($holidayData)
                            <a href="{{ route('company-holidays.edit', $holidayData['id']) }}"
                               class="px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit {{ $selectedYear }} Holidays
                            </a>
                        @else
                            <a href="{{ route('company-holidays.create') }}?year={{ $selectedYear }}"
                               class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create {{ $selectedYear }} Holidays
                            </a>
                        @endif

                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Mandatory</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Optional</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar View -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            @php
                // Prepare holiday dates for easy lookup
                $mandatoryHolidayDates = [];
                $optionalHolidayDates = [];
                $holidayNames = [];

                if ($holidayData) {
                    if (isset($holidayData['mandatory_holidays']) && is_array($holidayData['mandatory_holidays'])) {
                        foreach ($holidayData['mandatory_holidays'] as $holiday) {
                            $date = \Carbon\Carbon::parse($holiday['date']);
                            $mandatoryHolidayDates[$date->format('Y-m-d')] = true;
                            $holidayNames[$date->format('Y-m-d')] = $holiday['name'];
                        }
                    }

                    if (isset($holidayData['optional_holidays']) && is_array($holidayData['optional_holidays'])) {
                        foreach ($holidayData['optional_holidays'] as $holiday) {
                            $date = \Carbon\Carbon::parse($holiday['date']);
                            $optionalHolidayDates[$date->format('Y-m-d')] = true;
                            $holidayNames[$date->format('Y-m-d')] = $holiday['name'];
                        }
                    }
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($months as $monthNum => $monthName)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">{{ $monthName }} {{ $selectedYear }}</h3>

                        <!-- Days of week header -->
                        <div class="grid grid-cols-7 gap-1 mb-2">
                            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                <div class="text-center text-xs font-medium text-gray-500 py-1">{{ $day }}</div>
                            @endforeach
                        </div>

                        <!-- Calendar days -->
                        <div class="grid grid-cols-7 gap-1">
                            @php
                                $firstDayOfMonth = \Carbon\Carbon::create($selectedYear, $monthNum, 1);
                                $daysInMonth = $firstDayOfMonth->daysInMonth;
                                $startDayOfWeek = $firstDayOfMonth->dayOfWeek;

                                // Add empty cells for days before the first day of the month
                                for ($i = 0; $i < $startDayOfWeek; $i++) {
                                    echo '<div class="h-8"></div>';
                                }

                                // Add cells for each day of the month
                                for ($day = 1; $day <= $daysInMonth; $day++) {
                                    $date = \Carbon\Carbon::create($selectedYear, $monthNum, $day);
                                    $dateString = $date->format('Y-m-d');
                                    $isToday = $date->isToday();
                                    $isMandatoryHoliday = isset($mandatoryHolidayDates[$dateString]);
                                    $isOptionalHoliday = isset($optionalHolidayDates[$dateString]);
                                    $holidayName = $holidayNames[$dateString] ?? '';

                                    $classes = 'h-8 w-8 text-center text-sm leading-8 rounded-full relative group cursor-pointer hover:bg-gray-100 transition-colors';

                                    if ($isToday) {
                                        $classes .= ' bg-blue-100 text-blue-800 font-semibold';
                                    } elseif ($isMandatoryHoliday) {
                                        $classes .= ' bg-blue-500 text-white font-semibold';
                                    } elseif ($isOptionalHoliday) {
                                        $classes .= ' bg-green-500 text-white font-semibold';
                                    } else {
                                        $classes .= ' text-gray-700';
                                    }

                                    echo '<div class="' . $classes . '" title="' . ($holidayName ?: $date->format('M j, Y')) . '">';
                                    echo $day;

                                    if ($holidayName) {
                                        echo '<div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">';
                                        echo $holidayName;
                                        echo '</div>';
                                    }

                                    echo '</div>';
                                }
                            @endphp
                        </div>
                    </div>
                @endforeach
            </div>

            @if(!$holidayData)
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">No holiday data found for {{ $selectedYear }}</p>
                    <p class="text-gray-400 text-sm mt-1">Create holiday data for this year to see the calendar.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection