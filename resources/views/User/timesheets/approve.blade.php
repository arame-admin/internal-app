@extends('layouts.app')

@section('title', 'Approve Timesheets')

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Approve Timesheets</h2>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Pending Timesheet Entries</h3>
            </div>
            
            @if($pending->isEmpty())
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No pending timesheets</p>
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
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pending as $entry)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $entry->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $entry->user->department?->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $entry->date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 font-bold text-blue-600 text-lg">{{ number_format($entry->hours, 2) }} hrs</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">{{ Str::limit($entry->description ?? 'No description', 60) }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <form action="{{ route('manager.timesheets.approve.update', $entry->id) }}" method="POST" class="inline">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">Approve</button>
                                        </form>
                                        <button type="button" 
                                                class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700"
                                                onclick="showRejectModal('{{ route('manager.timesheets.approve.update', $entry->id) }}')">
                                            Reject
                                        </button>
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
