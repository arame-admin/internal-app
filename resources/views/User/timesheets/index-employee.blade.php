@extends('layouts.app')

@section('title', 'My Timesheets')

@php
$weeklyTotal = \App\Models\Timesheet::weeklyTotal(auth()->id());
$userRole = auth()->user()->role_id ?? 0;
$timesheetRoutePrefix = $userRole == 2 ? 'manager.' : 'employee.';
@endphp

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">My Timesheets</h2>
                <p class="text-gray-500 mt-1">Track your monthly working hours</p>
            </div>
        </div>
        
        <!-- Monthly & Weekly Total Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">This Month Total</p>
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($monthlyTotal, 2) }} <span class="text-lg font-normal">hrs</span></p>
                        <p class="text-xs text-gray-400">Target: 160 hrs</p>
                    </div>
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">This Week Total</p>
                        <p class="text-3xl font-bold {{ $weeklyTotal >= 40 ? 'text-green-600' : 'text-yellow-600' }}">{{ number_format($weeklyTotal, 2) }} <span class="text-lg font-normal">hrs</span></p>
                        <p class="text-xs text-gray-400">Target: 40 hrs</p>
                    </div>
                    <div class="w-16 h-16 {{ $weeklyTotal >= 40 ? 'bg-green-100' : 'bg-yellow-100' }} rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8 {{ $weeklyTotal >= 40 ? 'text-green-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Timesheet Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">
                    {{ $timesheets->count() }} entries for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
                </h3>
                <a href="{{ route($timesheetRoutePrefix . 'timesheets.apply', ['year' => $year, 'month' => $month]) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    Log Hours
                </a>
            </div>
            
            @if($timesheets->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-lg">No timesheet entries</p>
                    <p class="text-sm mt-1">Log your first entry to get started</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Time</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Break</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Hours</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($timesheets as $entry)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $entry->date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if($entry->start_time && $entry->end_time)
                                            {{ $entry->start_time }} - {{ $entry->end_time }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $entry->break_duration ? $entry->break_duration . ' hrs' : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-blue-600">{{ number_format($entry->hours, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-md truncate">{{ Str::limit($entry->description, 80) }}</td>
                                    <td class="px-6 py-4">
                                        @php $statusClass = match($entry->status) { 'draft' => 'bg-gray-100 text-gray-700', 'pending' => 'bg-yellow-100 text-yellow-700', 'approved' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700', }; @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ ucfirst($entry->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

