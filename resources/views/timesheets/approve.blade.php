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
                                        <form action="{{ route('manager.timesheets.approve.update', $entry->id) }}" method="POST" class="inline">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">Reject</button>
                                        </form>
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

