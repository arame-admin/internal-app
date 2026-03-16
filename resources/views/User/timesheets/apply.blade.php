@extends('layouts.app')

@section('title', 'Log Timesheet')

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-2xl mx-auto">
        <!-- Monthly & Weekly Total -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">This Month</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($monthlyTotal, 2) }} <span class="text-sm font-normal">/ 160 hrs</span></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">This Week</p>
                        <p class="text-2xl font-bold {{ $weeklyTotal >= 40 ? 'text-green-600' : 'text-yellow-600' }}">{{ number_format($weeklyTotal, 2) }} <span class="text-sm font-normal">/ 40 hrs</span></p>
                        @if($weeklyTotal < 40 && $weeklyTotal > 0)
                            <p class="text-xs text-red-500 mt-1">{{ number_format(40 - $weeklyTotal, 2) }} hrs remaining</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Log New Entry Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Log Hours</h2>
            <form action="{{ route('employee.timesheets.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                        <input type="date" id="date" name="date" required max="{{ now()->format('Y-m-d') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time <span class="text-red-500">*</span></label>
                            <input type="time" id="start_time" name="start_time" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time <span class="text-red-500">*</span></label>
                            <input type="time" id="end_time" name="end_time" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label for="break_duration" class="block text-sm font-medium text-gray-700 mb-2">Break Duration (hours)</label>
                        <input type="number" id="break_duration" name="break_duration" step="0.25" min="0" max="4" value="1"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="e.g., 1 for 1 hour break">
                        <p class="text-xs text-gray-500 mt-1">Break time will be excluded from total hours</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-700">
                            <strong>Note:</strong> Minimum 6.5 hours required per day (excluding break). 
                            Weekly target is 40 hours.
                        </p>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="What did you work on today? (optional)"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('employee.timesheets.index') }}" class="px-5 py-2.5 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Log Hours
                    </button>
                </div>
            </form>
        </div>

        <!-- Existing Entries -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Draft / Pending / Rejected Entries</h3>
            </div>
            @if($existing->isEmpty())
                <div class="p-8 text-center text-gray-500">No editable entries. All approved entries shown in list.</div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($existing as $entry)
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $entry->date->format('M d, Y') }}</p>
                                    <p class="text-sm text-gray-500">
                                        @if($entry->start_time && $entry->end_time)
                                            {{ $entry->start_time }} - {{ $entry->end_time }} 
                                            @if($entry->break_duration > 0)
                                                (Break: {{ $entry->break_duration }} hrs)
                                            @endif
                                        @endif
                                        = {{ number_format($entry->hours, 2) }} hrs
                                    </p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs {{ match($entry->status) { 'draft' => 'bg-gray-100 text-gray-700', 'pending' => 'bg-yellow-100 text-yellow-700', 'rejected' => 'bg-red-100 text-red-700' } }}">
                                    {{ ucfirst($entry->status) }}
                                </span>
                            </div>
                            @if($entry->rejection_reason)
                                <div class="mb-3 p-2 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-700"><strong>Rejection Reason:</strong> {{ $entry->rejection_reason }}</p>
                                </div>
                            @endif
                            @if($entry->description)
                                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($entry->description, 100) }}</p>
                            @endif
                            @if($entry->status == 'draft' || $entry->status == 'rejected')
                                <form action="{{ route('employee.timesheets.updateDraft', $entry->id) }}" method="POST" class="inline-flex flex-wrap items-center gap-2">
                                    @csrf @method('PATCH')
                                    <input type="time" name="start_time" value="{{ $entry->start_time }}" class="px-2 py-1 border rounded text-sm" required>
                                    <span class="text-gray-500">to</span>
                                    <input type="time" name="end_time" value="{{ $entry->end_time }}" class="px-2 py-1 border rounded text-sm" required>
                                    <input type="number" name="break_duration" value="{{ $entry->break_duration ?? 1 }}" step="0.25" min="0" max="4" 
                                        class="w-16 px-2 py-1 border rounded text-sm" title="Break (hours)">
                                    <button type="submit" class="px-4 py-1.5 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Update</button>
                                </form>
                                @if($entry->status == 'draft')
                                    <form action="{{ route('employee.timesheets.submit', $entry->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-1.5 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">Submit for Approval</button>
                                    </form>
                                @endif
                                <form action="{{ route('employee.timesheets.destroy', $entry->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this entry?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-4 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm hover:bg-red-200">Delete</button>
                                </form>
                            @elseif($entry->status == 'pending')
                                <p class="text-sm text-yellow-600">This entry is pending approval.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

