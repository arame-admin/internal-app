@extends('layouts.app')

@section('title', 'Approve Timesheets')

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Approve Timesheets</h2>
            <a href="{{ route('admin.timesheets.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Timesheets
            </a>
        </div>
        
        <!-- Status Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="flex gap-4">
                <a href="{{ route('admin.timesheets.approve', ['status' => 'all']) }}" 
                   class="px-4 py-2 rounded-lg {{ $status == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All
                </a>
                <a href="{{ route('admin.timesheets.approve', ['status' => 'pending']) }}" 
                   class="px-4 py-2 rounded-lg {{ $status == 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Pending
                </a>
                <a href="{{ route('admin.timesheets.approve', ['status' => 'approved']) }}" 
                   class="px-4 py-2 rounded-lg {{ $status == 'approved' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Approved
                </a>
                <a href="{{ route('admin.timesheets.approve', ['status' => 'rejected']) }}" 
                   class="px-4 py-2 rounded-lg {{ $status == 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Rejected
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @if($timesheets->isEmpty())
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No timesheets found</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approved By</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($timesheets as $entry)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $entry->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $entry->user->department?->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $entry->date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 font-bold text-blue-600 text-lg">{{ number_format($entry->hours, 2) }} hrs</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">{{ Str::limit($entry->description ?? 'No description', 60) }}</td>
                                    <td class="px-6 py-4">
                                        @php 
                                            $statusClass = match($entry->status) {
                                                'draft' => 'bg-gray-100 text-gray-700',
                                                'pending' => 'bg-yellow-100 text-yellow-700',
                                                'approved' => 'bg-green-100 text-green-700',
                                                'rejected' => 'bg-red-100 text-red-700',
                                            }; 
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ ucfirst($entry->status) }}
                                        </span>
                                        @if($entry->rejection_reason)
                                            <div class="text-xs text-red-600 mt-1" title="{{ $entry->rejection_reason }}">
                                                Reason: {{ Str::limit($entry->rejection_reason, 30) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $entry->approver?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        @if($entry->status == 'pending')
                                            <form action="{{ route('admin.timesheets.approve.update', $entry->id) }}" method="POST" class="inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">Approve</button>
                                            </form>
                                            <button type="button" onclick="showRejectModal({{ $entry->id }})" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">Reject</button>
                                        @elseif($entry->status == 'rejected')
                                            <button type="button" onclick="resetToPending({{ $entry->id }})" class="px-3 py-1 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">Reset</button>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $timesheets->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Reject Timesheet</h3>
        <form id="rejectForm" method="POST">
            @csrf
            @method('PUT')
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
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(id) {
    const form = document.getElementById('rejectForm');
    form.action = '{{ route("admin.timesheets.approve.update", ":id") }}'.replace(':id', id);
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function resetToPending(id) {
    if (confirm('Are you sure you want to reset this timesheet to pending?')) {
        fetch('{{ route("admin.timesheets.approve.update", ":id") }}'.replace(':id', id), {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: 'pending' })
        }).then(() => window.location.reload());
    }
}
</script>
@endsection
