@extends('layouts.app')

@section('title', 'Approve Timesheets')

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Approve Timesheets</h2>

        <div class="space-y-6">
            @if($pending->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No pending timesheets</p>
                </div>
            @else
                @foreach($groupedTimesheets as $key => $group)
                    @php
                        $firstEntry = $group->first();
                        $date = $firstEntry->date;
                        $user = $firstEntry->user;
                        $totalHours = $group->sum('hours');
                    @endphp
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Submission Header -->
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-bold">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $date->format('l, F d, Y') }} • {{ $user->department?->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-xl font-bold text-blue-600">{{ number_format($totalHours, 2) }} hrs</span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">
                                    Pending
                                </span>
                            </div>
                        </div>

                        <!-- Timesheet Entries for this date -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Task</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($group as $entry)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $entry->project?->name ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $entry->task ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $entry->start_time ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $entry->end_time ?? '-' }}</td>
                                            <td class="px-6 py-4 font-bold text-blue-600">{{ number_format($entry->hours, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">{{ Str::limit($entry->description ?? 'No description', 50) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Approval Actions for the whole date -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                            <form action="{{ route('manager.timesheets.approve.byDate') }}" method="POST" class="flex items-center gap-4">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="date" value="{{ $date->format('Y-m-d') }}">
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <input type="hidden" name="status" value="approved" id="status-{{ $key }}">
                                <input type="hidden" name="rejection_reason" id="reason-{{ $key }}">
                                
                                <button type="button" onclick="approveDate('{{ $key }}')" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                                    Approve All ({{ $group->count() }} entries)
                                </button>
                                <button type="button" onclick="showRejectForDate('{{ $key }}')" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                                    Reject All
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Reject All Timesheets</h3>
        <p class="text-sm text-gray-600 mb-4">This will reject all timesheet entries for this date.</p>
        <form id="rejectForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="date" id="modalDate">
            <input type="hidden" name="user_id" id="modalUserId">
            <input type="hidden" name="status" value="rejected">
            <div class="mb-4">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                <textarea id="rejection_reason" name="rejection_reason" rows="3" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideRejectModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Reject All
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function approveDate(key) {
    document.getElementById('status-' + key).value = 'approved';
    const form = document.querySelector(`#status-${key}`).closest('form');
    form.submit();
}

function showRejectForDate(key) {
    // Get the form for this group
    const form = document.querySelector(`#status-${key}`).closest('form');
    const dateInput = form.querySelector('input[name="date"]');
    const userIdInput = form.querySelector('input[name="user_id"]');
    
    document.getElementById('modalDate').value = dateInput.value;
    document.getElementById('modalUserId').value = userIdInput.value;
    document.getElementById('rejectForm').action = '{{ route("manager.timesheets.approve.byDate") }}';
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection
