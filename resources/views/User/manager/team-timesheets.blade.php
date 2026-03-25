@extends('layouts.app')

@section('title', 'Team Timesheets')

@section('content')
@php
$userRole = auth()->user()->role_id ?? 0;
$timesheetRoutePrefix = $userRole == 2 ? 'manager.' : 'employee.';
@endphp

<div class="p-6 mt-16">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Team Timesheets</h2>
            <p class="text-gray-500 mt-1">Manage and view timesheets for your team members</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="w-40">
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <select name="year" id="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @for($y = now()->year; $y >= now()->year - 2; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="w-40">
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                    <select name="month" id="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="w-48">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Hours</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($totalHours, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Approved Hours</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($approvedHours, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pending Hours</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ number_format($pendingHours, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Members Summary -->
        @if($subordinates->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">
                    Team Members Summary- {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
                </h3>
                <a href="{{ route('manager.timesheets.approve') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    Pending Approvals ({{ $pendingHours > 0 ? $timesheets->where('status', 'pending')->count() : 0 }})
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Hours</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Approved</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pending</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Entries</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($subordinates as $subordinate)
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('manager.timesheets.team.detail', ['user_id' => $subordinate->id, 'year' => $year, 'month' => $month]) }}'">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-bold text-xs">{{ substr($subordinate->name, 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $subordinate->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $subordinate->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $subordinate->department?->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">
                                {{ number_format($userSummary[$subordinate->id]['total_hours'] ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-green-600">
                                {{ number_format($userSummary[$subordinate->id]['approved_hours'] ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-yellow-600">
                                {{ number_format($userSummary[$subordinate->id]['pending_hours'] ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-600">
                                {{ $userSummary[$subordinate->id]['count'] ?? 0 }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
       

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Timesheet</h3>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="rejected">
                
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                    <textarea name="rejection_reason" 
                              id="rejection_reason" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              required></textarea>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            onclick="hideRejectModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal(action) {
    document.getElementById('rejectForm').action = action;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejection_reason').value = '';
}
</script>
@endsection
