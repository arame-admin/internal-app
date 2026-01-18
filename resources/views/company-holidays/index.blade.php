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

        <!-- Floating Add Button -->
        <a href="{{ route('company-holidays.create') }}" class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-full p-4 shadow-lg hover:from-blue-600 hover:to-indigo-700 transition-colors z-50" title="Add New Holiday Year">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </a>

        <!-- Filters & Search -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-4 border-b border-gray-100">
                <form action="{{ route('company-holidays.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Search -->
                    <div class="relative flex-1 max-w-md">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by year..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>

                    <!-- Filters -->
                    <div class="flex items-center space-x-3">
                        <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>

                        <select name="sort" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white">
                            <option value="">Sort By</option>
                            <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Year</option>
                        </select>

                        <button type="submit" class="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </button>

                        @if(request()->anyFilled(['search', 'status', 'sort']))
                            <a href="{{ route('company-holidays.index') }}" class="px-4 py-2 text-sm text-red-600 hover:text-red-700">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Company Holidays Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 text-left">
                            <a href="{{ route('company-holidays.index', ['sort' => 'year', 'search' => request('search'), 'status' => request('status')]) }}" class="flex items-center space-x-1 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                <span>Year</span>
                                @if(request('sort') == 'year')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-center">
                            <a href="{{ route('company-holidays.index', ['sort' => 'mandatory', 'search' => request('search'), 'status' => request('status')]) }}" class="flex items-center justify-center space-x-1 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                <span>Mandatory Holidays</span>
                                @if(request('sort') == 'mandatory')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-center">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Mandatory Holidays</span>
                        </th>
                        <th class="px-6 py-4 text-center">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Optional Holidays</span>
                        </th>
                        <th class="px-6 py-4 text-center">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Holidays</span>
                        </th>
                        <th class="px-6 py-4 text-center">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</span>
                        </th>
                        <th class="px-6 py-4 text-right">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($companyHolidays as $holiday)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $holiday['year'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm">
                                @if(isset($holiday['mandatory_holidays']) && is_array($holiday['mandatory_holidays']))
                                    <div class="space-y-1">
                                        @foreach(array_slice($holiday['mandatory_holidays'], 0, 2) as $holidayItem)
                                            <div class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                {{ \Carbon\Carbon::parse($holidayItem['date'])->format('M d') }}: {{ Str::limit($holidayItem['name'], 12) }}
                                            </div>
                                        @endforeach
                                        @if(count($holiday['mandatory_holidays']) > 2)
                                            <div class="text-xs text-gray-500">+{{ count($holiday['mandatory_holidays']) - 2 }} more</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $holiday['mandatory_holidays'] ?? 0 }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm">
                                @if(isset($holiday['optional_holidays']) && is_array($holiday['optional_holidays']) && !empty($holiday['optional_holidays']))
                                    <div class="space-y-1">
                                        @foreach(array_slice($holiday['optional_holidays'], 0, 2) as $holidayItem)
                                            <div class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                                {{ \Carbon\Carbon::parse($holidayItem['date'])->format('M d') }}: {{ Str::limit($holidayItem['name'], 12) }}
                                            </div>
                                        @endforeach
                                        @if(count($holiday['optional_holidays']) > 2)
                                            <div class="text-xs text-gray-500">+{{ count($holiday['optional_holidays']) - 2 }} more</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        0
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                @php
                                    $mandatoryCount = isset($holiday['mandatory_holidays']) && is_array($holiday['mandatory_holidays']) ? count($holiday['mandatory_holidays']) : ($holiday['mandatory_holidays'] ?? 0);
                                    $optionalCount = isset($holiday['optional_holidays']) && is_array($holiday['optional_holidays']) ? count($holiday['optional_holidays']) : 0;
                                    echo $mandatoryCount + $optionalCount;
                                @endphp
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($holiday['status'] == 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('company-holidays.status', $holiday['id']) }}" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Change Status">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('company-holidays.edit', $holiday['id']) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('company-holidays.destroy', $holiday['id']) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this holiday year?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">No company holidays found</p>
                                <p class="text-gray-400 text-sm mt-1">Try adjusting your search or filter</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if(isset($companyHolidays))
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-sm text-gray-500">Showing {{ count($companyHolidays) }} of {{ count($companyHolidays) }} holiday years</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection