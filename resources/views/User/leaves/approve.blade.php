@extends('layouts.app')

@section('title', 'Approve Leaves')

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                @if(isset($isAdmin) && $isAdmin)
                    Admin Leave Approval
                @else
                    Approve Team Leaves
                @endif
            </h2>
            <p class="text-gray-500 mt-1">
                @if(isset($isAdmin) && $isAdmin)
                    Review all pending leave requests across the organization
                @else
                    Review and approve leave requests from your team
                @endif
            </p>
        </div>

        <!-- Pending Leaves Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">
                    @if(isset($isAdmin) && $isAdmin)
                        All Pending Leave Requests
                    @else
                        Team Pending Leave Requests
                    @endif
                    <span class="ml-2 px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                        {{ $pendingLeaves->count() }}
                    </span>
                </h3>
            </div>

            
            <div class="p-6">
                @if($pendingLeaves->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">No pending leave requests</p>
                        <p class="text-gray-400 text-sm mt-1">Leave requests from your team will appear here</p>
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
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Applied On</th>
                                    <th class="text-center py-3 px-4 text-sm font-semibold text-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingLeaves as $leave)
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
                                        <td class="py-3 px-4 text-gray-500 text-sm">
                                            {{ $leave->created_at->format('d M, Y') }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <form action="{{ isset($isAdmin) && $isAdmin ? route('admin.leaves.applications.approve', $leave->id) : route('manager.leaves.update', $leave->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" 
                                                        class="px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                                                        Approve
                                                    </button>
                                                </form>

                                                <button type="button" 
                                                    onclick="showRejectModal({{ $leave->id }})"
                                                    class="px-3 py-1.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                                    Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Leave Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $pendingLeaves->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Approved</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">0</p>
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
                        <p class="text-2xl font-bold text-red-600 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Reject Leave Request</h3>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="rejected">
            <div class="p-6">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Reason for Rejection <span class="text-red-500">*</span>
                </label>
                <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    placeholder="Enter reason for rejection..."></textarea>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button type="button" onclick="hideRejectModal()"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showRejectModal(leaveId) {
        const form = document.getElementById('rejectForm');
        form.action = '{{ isset($isAdmin) && $isAdmin ? route("admin.leaves.applications.approve", ":id") : route("manager.leaves.update", ":id") }}'.replace(':id', leaveId);

        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection
