@extends('layouts.app')

@section('title', 'Approve Timesheet - ' . $timesheet->date->format('M d, Y'))

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Timesheet Approval</h2>
            <a href="{{ route('admin.timesheets.approve') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Approvals
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Employee Info Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-bold text-lg">{{ substr($timesheet->user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $timesheet->user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $timesheet->user->department?->name ?? 'N/A' }} • {{ $timesheet->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Timesheet Details -->
            <div class="p-6">
                <div class="grid grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Date</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $timesheet->date->format('l, F d, Y') }}</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        @php 
                            $statusClass = match($timesheet->status) {
                                'draft' => 'bg-gray-100 text-gray-700',
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'approved' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                            }; 
                        @endphp
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                            {{ ucfirst($timesheet->status) }}
                        </span>
                    </div>

                    <!-- Hours -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Hours Worked</label>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($timesheet->hours, 2) }} hours</p>
                    </div>

                    <!-- Project -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Project</label>
                        <p class="text-gray-900">{{ $timesheet->project?->name ?? 'No Project' }}</p>
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Start Time</label>
                        <p class="text-gray-900">{{ $timesheet->start_time ?? '-' }}</p>
                    </div>

                    <!-- End Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">End Time</label>
                        <p class="text-gray-900">{{ $timesheet->end_time ?? '-' }}</p>
                    </div>

                    <!-- Break Duration -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Break Duration</label>
                        <p class="text-gray-900">{{ $timesheet->break_duration ? $timesheet->break_duration . ' hours' : '-' }}</p>
                    </div>

                    <!-- Approved By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Approved By</label>
                        <p class="text-gray-900">{{ $timesheet->approver?->name ?? 'Not Approved' }}</p>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500 mb-2">Description / Task Details</label>
                    <div class="bg-gray-50 rounded-lg p-4 text-gray-700">
                        {{ $timesheet->description ?? 'No description provided' }}
                    </div>
                </div>

                <!-- Task -->
                @if($timesheet->task)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500 mb-2">Task</label>
                    <div class="bg-gray-50 rounded-lg p-4 text-gray-700">
                        {{ $timesheet->task }}
                    </div>
                </div>
                @endif

                <!-- Rejection Reason -->
                @if($timesheet->rejection_reason)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-red-500 mb-2">Rejection Reason</label>
                    <div class="bg-red-50 rounded-lg p-4 text-red-700">
                        {{ $timesheet->rejection_reason }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Approval Actions -->
            @if($timesheet->status == 'pending')
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                <form action="{{ route('admin.timesheets.approve.update', $timesheet->id) }}" method="POST" id="approvalForm">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="status" id="approvalStatus">
                    
                    <div id="rejectionReasonDiv" class="hidden mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                            placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="submitApproval('approved')" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                            Approve
                        </button>
                        <button type="button" onclick="showRejectionReason()" 
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                            Reject
                        </button>
                    </div>
                </form>
            </div>
            @elseif($timesheet->status == 'rejected')
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                <form action="{{ route('admin.timesheets.approve.update', $timesheet->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="pending">
                    <button type="submit" 
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium">
                        Reset to Pending
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function submitApproval(status) {
    document.getElementById('approvalStatus').value = status;
    
    if (status === 'rejected') {
        const reason = document.getElementById('rejection_reason').value;
        if (!reason.trim()) {
            alert('Please provide a rejection reason.');
            return;
        }
    }
    
    document.getElementById('approvalForm').submit();
}

function showRejectionReason() {
    document.getElementById('rejectionReasonDiv').classList.remove('hidden');
    document.getElementById('rejection_reason').focus();
}
</script>
@endsection
