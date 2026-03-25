@extends('layouts.app')

@section('title', 'Timesheet Details - ' . $subordinate->name)

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('manager.timesheets.team', ['year' => $year, 'month' => $month]) }}" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $subordinate->name }}</h2>
                    <p class="text-gray-500">{{ $subordinate->department?->name ?? 'N/A' }} • {{ $subordinate->email }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <p class="text-sm text-gray-500">Total Hours</p>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($totalHours, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <p class="text-sm text-gray-500">Approved Hours</p>
                <p class="text-3xl font-bold text-green-600">{{ number_format($approvedHours, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <p class="text-sm text-gray-500">Pending Hours</p>
                <p class="text-3xl font-bold text-yellow-600">{{ number_format($pendingHours, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <p class="text-sm text-gray-500">Rejected Hours</p>
                <p class="text-3xl font-bold text-red-600">{{ number_format($rejectedHours ?? 0, 2) }}</p>
            </div>
        </div>

        <!-- Timesheet Entries by Date -->
        @if($timesheets->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <p class="text-gray-500 text-lg">No timesheet entries found</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($groupedTimesheets as $dateKey => $group)
                    @php
                        $firstEntry = $group->first();
                        $date = $firstEntry->date;
                        // Only count approved hours in the day total
                        $totalHours = $group->where('status', 'approved')->sum('hours');
                        $hasPendingEntries = $group->contains('status', 'pending');
                    @endphp
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Date Header -->
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-100 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <span class="font-medium text-gray-900">{{ $date->format('l, F d, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-blue-600">{{ number_format($totalHours, 2) }} hrs</span>
                                @php 
                                    $statusClass = match($firstEntry->status) {
                                        'draft' => 'bg-gray-100 text-gray-700',
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    }; 
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($firstEntry->status) }}
                                </span>
                                @if($hasPendingEntries)
                                    <form action="{{ route('manager.timesheets.approve.byDate') }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="date" value="{{ $date->format('Y-m-d') }}">
                                        <input type="hidden" name="user_id" value="{{ $subordinate->id }}">
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700" title="Approve All">
                                            Approve All
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Entries Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                                        <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Task</th>
                                        <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Start</th>
                                        <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">End</th>
                                        <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                                        <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                        <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($group as $entry)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-3 text-sm text-gray-900">{{ $entry->project?->name ?? '-' }}</td>
                                            <td class="px-6 py-3 text-sm text-gray-600">{{ $entry->task ?? '-' }}</td>
                                            <td class="px-6 py-3 text-sm text-gray-600">{{ $entry->start_time ?? '-' }}</td>
                                            <td class="px-6 py-3 text-sm text-gray-600">{{ $entry->end_time ?? '-' }}</td>
                                            <td class="px-6 py-3 font-bold text-blue-600">{{ number_format($entry->hours, 2) }}</td>
                                            <td class="px-6 py-3 text-sm text-gray-600 max-w-md truncate">{{ Str::limit($entry->description, 60) }}</td>
                                            <td class="px-6 py-3">
                                                @if($entry->status === 'pending')
                                                    <div class="flex items-center gap-2">
                                                        <form action="{{ route('manager.timesheets.approve.update', $entry->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="approved">
                                                            <input type="hidden" name="year" value="{{ $year }}">
                                                            <input type="hidden" name="month" value="{{ $month }}">
                                                            <button type="submit" class="p-1 text-green-600 hover:bg-green-50 rounded" title="Approve">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        <button type="button" class="p-1 text-red-600 hover:bg-red-50 rounded" title="Reject" onclick="showRejectModal({{ $entry->id }})">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @else
                                                    @php
                                                        $entryStatusClass = match($entry->status) {
                                                            'draft' => 'text-gray-500',
                                                            'pending' => 'text-yellow-500',
                                                            'approved' => 'text-green-500',
                                                            'rejected' => 'text-red-500',
                                                        };
                                                    @endphp
                                                    <span class="text-xs font-medium {{ $entryStatusClass }}">{{ ucfirst($entry->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Reject Timesheet Entry</h3>
        <form id="rejectForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="rejected">
            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="month" value="{{ $month }}">
            <div class="mb-4">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                <textarea id="rejection_reason" name="rejection_reason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Please provide a reason for rejection..." required></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(entryId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = '/manager/timesheets/' + entryId + '/approve';
    modal.classList.remove('hidden');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    const textarea = document.getElementById('rejection_reason');
    textarea.value = '';
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection
