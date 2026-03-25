@extends('layouts.app')

@section('title', 'My Leaves')

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">My Leaves</h2>
                <p class="text-gray-500 mt-1">View your leave balance and applications</p>
            </div>
        </div>
        
        <!-- Leave Balance Cards -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Leave Balance for {{ $year }}</h3>
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Sick Leave</p>
                            <p class="text-2xl font-bold text-red-600">{{ $leaveBalance['sick_leave_balance'] ?? 0 }}</p>
                            <p class="text-xs text-gray-400">of {{ $leaveBalance['sick_leave'] ?? 0 }} days</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Casual Leave</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $leaveBalance['casual_leave_balance'] ?? 0 }}</p>
                            <p class="text-xs text-gray-400">of {{ $leaveBalance['casual_leave'] ?? 0 }} days</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Earned Leave</p>
                            <p class="text-2xl font-bold text-green-600">{{ $leaveBalance['earned_leave_balance'] ?? 0 }}</p>
                            <p class="text-xs text-gray-400">of {{ $leaveBalance['earned_leave'] ?? 0 }} days</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Applied Leaves List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Leave Applications</h3>
            </div>
            
            @if($appliedLeaves->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    No leave applications found.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied On</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($appliedLeaves as $leave)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                <td class="px-6 py-4 text-sm text-gray-600">
                                        @if($leave->duration_type === 'half_day')
                                            {{ $leave->start_date->format('d M Y') }} ({{ ucwords(str_replace('_', ' ', $leave->half_period ?? '')) }})
                                        @else
                                            {{ $leave->start_date->format('d M, Y') }} - {{ $leave->end_date->format('d M, Y') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                        @if($leave->duration_type === 'half_day')
                                            {{ $leave->total_days }} day ({{ ucwords(str_replace('_', ' ', $leave->half_period ?? '')) }})
                                        @else
                                            {{ $leave->total_days }} day{{ $leave->total_days != 1 ? 's' : '' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClass = match($leave->status) {
                                                'pending' => 'bg-yellow-100 text-yellow-700',
                                                'approved' => 'bg-green-100 text-green-700',
                                                'rejected' => 'bg-red-100 text-red-700',
                                                default => 'bg-gray-100 text-gray-700'
                                            };
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $leave->created_at->format('d M, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($leave->status === 'pending' || $leave->status === 'approved')
                                            <a href="{{ route('employee.leaves.edit', $leave->id) }}" 
                                                class="text-blue-600 hover:text-blue-800 font-medium text-xs mr-3">
                                                Edit
                                            </a>
                                            <form action="{{ route('employee.leaves.cancel', $leave->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 font-medium text-xs"
                                                    onclick="return confirm('Are you sure you want to cancel this leave application?')">
                                                    Cancel
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        
        <!-- Floating Apply Leave Button -->
        <a href="{{ route('employee.leaves.apply') }}" 
            class="fixed bottom-8 right-8 bg-blue-600 hover:bg-blue-700 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg hover:shadow-xl transition-all transform hover:scale-110">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </a>
    </div>
</div>
@endsection
