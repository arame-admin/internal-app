@extends('layouts.app')

@section('title', 'Leave Applications - Admin')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Leave Applications</h1>
                    <p class="text-gray-500 mt-1">Manage all leave requests from employees</p>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center group hover:shadow-md transition-all">
                <div class="inline-flex items-center p-3 bg-gray-100 rounded-xl mb-4 group-hover:bg-gray-200 transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3"></path>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalRequests) }}</div>
                <div class="text-sm font-medium text-gray-900">Total Requests</div>
                <div class="text-sm text-gray-500">All leave applications</div>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center group hover:shadow-md transition-all">
                <div class="inline-flex items-center p-3 bg-yellow-100 rounded-xl mb-4 group-hover:bg-yellow-200 transition-colors">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($pendingCount) }}</div>
                <div class="text-sm font-medium text-gray-900">Pending</div>
                <div class="text-sm text-gray-500">Awaiting approval</div>
            </div>

            <!-- Approved -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center group hover:shadow-md transition-all">
                <div class="inline-flex items-center p-3 bg-green-100 rounded-xl mb-4 group-hover:bg-green-200 transition-colors">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($approvedCount) }}</div>
                <div class="text-sm font-medium text-gray-900">Approved</div>
                <div class="text-sm text-gray-500">Successfully processed</div>
            </div>

            <!-- Canceled -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center group hover:shadow-md transition-all">
                <div class="inline-flex items-center p-3 bg-red-100 rounded-xl mb-4 group-hover:bg-red-200 transition-colors">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($canceledCount) }}</div>
                <div class="text-sm font-medium text-gray-900">Canceled</div>
                <div class="text-sm text-gray-500">Withdrawn applications</div>
            </div>
        </div>

        <!-- Filters --> 
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6"> 
            <form method="GET" action="{{ route('admin.leaves.applications') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1 min-w-0">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Search by employee name or reason...">
                </div>
                <div class="flex-1 min-w-0">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>
                <div class="flex-1 min-w-0">
                    <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-2">Leave Type</label>
                    <select id="leave_type" name="leave_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        <option value="sick_leave" {{ request('leave_type') == 'sick_leave' ? 'selected' : '' }}>Sick Leave</option>
                        <option value="casual_leave" {{ request('leave_type') == 'casual_leave' ? 'selected' : '' }}>Casual Leave</option>
                        <option value="earned_leave" {{ request('leave_type') == 'earned_leave' ? 'selected' : '' }}>Earned Leave</option>
                    </select>
                </div>
                <div class="flex-1 min-w-0">
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <select id="year" name="year" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Years</option>
                        @for ($y = 2024; $y <= now()->year + 1; $y++)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex gap-2"> 
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Filter
                    </button>
                    <a href="{{ route('admin.leaves.applications') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Applications Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">All Leave Applications</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Leave Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dates</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Days</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Applied</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($applications as $application)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $application->user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $application->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $typeClass = match($application->leave_type) {
                                            'sick_leave' => 'bg-red-100 text-red-800',
                                            'casual_leave' => 'bg-blue-100 text-blue-800',
                                            'earned_leave' => 'bg-green-100 text-green-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $typeClass }}">
                                        {{ ucwords(str_replace('_', ' ', $application->leave_type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $application->start_date->format('M d, Y') }}</p>
                                        <p class="text-sm text-gray-500">to {{ $application->end_date->format('M d, Y') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900">{{ $application->total_days }} day{{ $application->total_days != 1 ? 's' : '' }}</span>
                                    @if($application->duration_type === 'half_day')
                                        <p class="text-xs text-gray-500 mt-0.5">{{ ucwords(str_replace('_', ' ', $application->half_period ?? '')) }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = match($application->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $application->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if($application->status === 'pending')
                                        <form action="{{ route('admin.leaves.applications.approve', $application->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                                                Approve
                                            </button>
                                        </form>
                                        <button onclick="showRejectModal({{ $application->id }})" class="px-4 py-2 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                            Reject
                                        </button>
                                    @else
                                        <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-lg">Complete</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">No applications found</h3>
                                        <p class="text-gray-500">Try adjusting your search criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $applications->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-900">Reject Leave Request</h3>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="rejected">
            <div class="p-6">
                <label for="rejection_reason" class="block text-sm font-semibold text-gray-700 mb-3">
                    Reason for Rejection <span class="text-red-500">*</span>
                </label>
                <textarea id="rejection_reason" name="rejection_reason" rows="4" required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                    placeholder="Please provide a clear reason for rejection..."></textarea>
            </div>
            <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                <button type="button" onclick="hideRejectModal()" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors shadow-sm">
                    Reject Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(applicationId) {
    document.getElementById('rejectForm').action = `/admin/leaves/applications/${applicationId}/approve`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejection_reason').value = '';
}
</script>
@endsection