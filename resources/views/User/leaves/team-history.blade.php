@extends('layouts.app')

@section('title', 'Team Leave History')

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Team Leave History</h2>
            <p class="text-gray-500 mt-1">View approved and rejected leave requests from your team</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <form method="GET" action="{{ route('manager.leaves.team.history') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                    <select name="leave_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ request('leave_type') == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="sick_leave" {{ request('leave_type') == 'sick_leave' ? 'selected' : '' }}>Sick Leave</option>
                        <option value="casual_leave" {{ request('leave_type') == 'casual_leave' ? 'selected' : '' }}>Casual Leave</option>
                        <option value="earned_leave" {{ request('leave_type') == 'earned_leave' ? 'selected' : '' }}>Earned Leave</option>
                    </select>
                </div>

                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
                    <select name="employee" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ request('employee') == 'all' ? 'selected' : '' }}>All Employees</option>
                        @foreach($subordinatesList as $subordinate)
                            <option value="{{ $subordinate->id }}" {{ request('employee') == $subordinate->id ? 'selected' : '' }}>
                                {{ $subordinate->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" {{ !request('year') ? 'selected' : '' }}>All Years</option>
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or reason..." 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('manager.leaves.team.history') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Approved</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $approvedCount }}</p>
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
                        <p class="text-sm text-gray-500">Rejected</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ $rejectedCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Processed</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $approvedCount + $rejectedCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">
                    Leave History
                    <span class="ml-2 px-3 py-1 bg-gray-100 text-gray-600 text-sm font-medium rounded-full">
                        {{ $historyLeaves->total() }}
                    </span>
                </h3>
                
                <!-- Sort Options -->
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Sort by:</span>
                    <select onchange="window.location.href = updateSortQuery(this.value)" 
                        class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="created_at-desc" {{ request('sort_by') == 'created_at' && request('sort_order') == 'desc' ? 'selected' : '' }}>Newest First</option>
                        <option value="created_at-asc" {{ request('sort_by') == 'created_at' && request('sort_order') == 'asc' ? 'selected' : '' }}>Oldest First</option>
                        <option value="start_date-desc" {{ request('sort_by') == 'start_date' && request('sort_order') == 'desc' ? 'selected' : '' }}>Start Date (Newest)</option>
                        <option value="start_date-asc" {{ request('sort_by') == 'start_date' && request('sort_order') == 'asc' ? 'selected' : '' }}>Start Date (Oldest)</option>
                        <option value="total_days-desc" {{ request('sort_by') == 'total_days' && request('sort_order') == 'desc' ? 'selected' : '' }}>Duration (Most)</option>
                        <option value="total_days-asc" {{ request('sort_by') == 'total_days' && request('sort_order') == 'asc' ? 'selected' : '' }}>Duration (Least)</option>
                    </select>
                </div>
            </div>
            
            <div class="p-6">
                @if($historyLeaves->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">No leave history found</p>
                        <p class="text-gray-400 text-sm mt-1">Try adjusting your filters</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Employee</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Department</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Leave Type</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Duration</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Days</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Reason</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Status</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Processed By</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Processed On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historyLeaves as $leave)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4">
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $leave->user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $leave->user->email }}</p>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-gray-600">
                                            {{ $leave->user->department?->name ?? 'N/A' }}
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $leaveTypeClass = match($leave->leave_type) {
                                                    'sick_leave' => 'bg-red-100 text-red-700',
                                                    'casual_leave' => 'bg-blue-100 text-blue-700',
                                                    'earned_leave' => 'bg-green-100 text-green-700',
                                                    default => 'bg-gray-100 text-gray-700'
                                                };
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $leaveTypeClass }}">
                                                {{ ucwords(str_replace('_', ' ', $leave->leave_type)) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-gray-600">
                                            <div>
                                                <p class="text-sm">{{ $leave->start_date->format('d M, Y') }}</p>
                                                <p class="text-xs text-gray-400">to {{ $leave->end_date->format('d M, Y') }}</p>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-gray-800 font-medium">
                                            @if($leave->duration_type === 'half_day')
                                                {{ $leave->total_days }} day ({{ ucwords(str_replace('_', ' ', $leave->half_period ?? '')) }})
                                            @else
                                                {{ $leave->total_days }} day{{ $leave->total_days != 1 ? 's' : '' }}
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-gray-600 text-sm max-w-xs">
                                            {{ Str::limit($leave->reason, 50) }}
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $statusClass = match($leave->status) {
                                                    'approved' => 'bg-green-100 text-green-700',
                                                    'rejected' => 'bg-red-100 text-red-700',
                                                    default => 'bg-gray-100 text-gray-700'
                                                };
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                                {{ ucfirst($leave->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-gray-600 text-sm">
                                            {{ $leave->approver?->name ?? 'N/A' }}
                                        </td>
                                        <td class="py-3 px-4 text-gray-500 text-sm">
                                            {{ $leave->approved_at ? $leave->approved_at->format('d M, Y') : $leave->updated_at->format('d M, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $historyLeaves->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function updateSortQuery(value) {
    const [sortBy, sortOrder] = value.split('-');
    const url = new URL(window.location.href);
    url.searchParams.set('sort_by', sortBy);
    url.searchParams.set('sort_order', sortOrder);
    return url.toString();
}
</script>
@endsection