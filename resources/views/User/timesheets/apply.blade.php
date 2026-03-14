@extends('layouts.app')

@section('title', 'Log Timesheet')

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-2xl mx-auto">
        <!-- Monthly Total -->
        <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Logged this month</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($monthlyTotal, 2) }} / 160 hrs</p>
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
                    <div>
                        <label for="hours" class="block text-sm font-medium text-gray-700 mb-2">Hours <span class="text-red-500">*</span></label>
                        <input type="number" id="hours" name="hours" step="0.25" min="0" max="24" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
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
                <h3 class="text-lg font-semibold text-gray-800">Draft / Pending Entries</h3>
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
                                    <p class="text-sm text-gray-500">{{ number_format($entry->hours, 2) }} hrs</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs {{ match($entry->status) { 'draft' => 'bg-gray-100 text-gray-700', 'pending' => 'bg-yellow-100 text-yellow-700' } }}">
                                    {{ ucfirst($entry->status) }}
                                </span>
                            </div>
                            @if($entry->description)
                                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($entry->description, 100) }}</p>
                            @endif
                            <form action="{{ route('employee.timesheets.updateDraft', $entry->id) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <input type="number" name="hours" value="{{ $entry->hours }}" step="0.25" min="0" max="24" 
                                    class="w-20 px-2 py-1 border rounded text-sm mr-2" required>
                                <textarea name="description" rows="1" class="w-64 px-3 py-1 border rounded text-sm mr-2" placeholder="Update desc">{{ $entry->description }}</textarea>
                                <button type="submit" class="px-4 py-1.5 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">Update & Submit</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

