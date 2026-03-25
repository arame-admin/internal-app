@extends('layouts.app')

@section('title', 'Timesheet Management')

@section('content')
<div class="p-6 mt-16">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Timesheet Management</h2>
                <p class="text-gray-500 mt-1">View and manage all employee timesheets</p>
            </div>
            <a href="{{ route('admin.timesheets.approve') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Pending Approvals
            </a>
        </div>
        
        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        @for($y = now()->year; $y >= now()->year - 2; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="w-60">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                    <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="w-60">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="w-56">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
                    <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">All Employees</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
                    Filter
                </button>
            </form>
        </div>
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
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
        </div>
        
        <!-- Employees Summary Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">
                    Employees Summary - {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
                </h3>
            </div>
            
            @if($timesheets->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <p class="text-lg">No timesheet entries found</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Hours</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Approved</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pending</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Entries</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                $userSummary = $timesheets->groupBy('user_id')->map(function ($userTimesheets) {
                                    return [
                                        'total_hours' => $userTimesheets->sum('hours'),
                                        'approved_hours' => $userTimesheets->where('status', 'approved')->sum('hours'),
                                        'pending_hours' => $userTimesheets->where('status', 'pending')->sum('hours'),
                                        'count' => $userTimesheets->count(),
                                    ];
                                });
                            @endphp
                            @foreach($userSummary as $userId => $summary)
                                @php
                                    $user = $timesheets->firstWhere('user_id', $userId)->user;
                                @endphp
                                <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.timesheets.detail', ['user_id' => $userId, 'year' => $year, 'month' => $month]) }}'">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-bold text-xs">{{ substr($user->name, 0, 2) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->department?->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900">
                                        {{ number_format($summary['total_hours'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-green-600">
                                        {{ number_format($summary['approved_hours'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-yellow-600">
                                        {{ number_format($summary['pending_hours'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-600">
                                        {{ $summary['count'] }}
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
