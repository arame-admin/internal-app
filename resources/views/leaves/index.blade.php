@extends('layouts.app')

@section('title', 'Leave Management')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="#" class="hover:text-blue-600">Leave Management</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Leaves</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Leaves Management</h1>
        </div>

        <!-- Leaves Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-gray-600">Manage employee leaves here. This section will include leave requests, approvals, and tracking.</p>
            <!-- Placeholder for leaves content -->
            <div class="mt-4">
                <p class="text-sm text-gray-500">Coming soon: Leave requests, balances, and history.</p>
            </div>
        </div>
    </div>
</div>
@endsection