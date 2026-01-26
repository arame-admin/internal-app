{{-- Users Index Page --}}
{{-- This page displays a list of users with search, filter, sort, and pagination functionality. --}}
{{-- It includes AJAX status toggling and responsive design. --}}

@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.users.index') }}" class="hover:text-blue-600">Users</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">List</li>
            </ol>
        </nav>

        <!-- Floating Add Button -->
        <a href="{{ route('admin.users.create') }}" class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-full p-4 shadow-lg hover:from-blue-600 hover:to-indigo-700 transition-colors z-50" title="Add New User">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </a>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                <p class="text-gray-700">Searching...</p>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-4 border-b border-gray-100">
                <form action="{{ route('admin.users.index') }}" method="GET" id="search-form" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Search -->
                    <div class="relative flex-1 max-w-md">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="w-full pl-10 pr-12 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" id="search-input">
                        <button type="button" id="search-button" class="absolute right-3 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-4 h-4 search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <svg class="w-4 h-4 clear-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Filters -->
                    <div class="flex items-center space-x-3">
                        <select name="department" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>

                        <select name="role" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>

                        <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>

                        <select name="sort" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white">
                            <option value="">Sort By</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Joined Date</option>
                        </select>

                        <button type="submit" class="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </button>

                        @if(request()->anyFilled(['search', 'department', 'role', 'status', 'sort']))
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm text-red-600 hover:text-red-700">
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">S.No</span>
                            </th>
                            @php
                                $currentSort = request('sort');
                                $currentDirection = request('direction', 'asc');
                            @endphp
                            <th class="px-6 py-4 text-left">
                                @php
                                    $column = 'name';
                                    $newDirection = ($currentSort == $column && $currentDirection == 'asc') ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ route('admin.users.index', ['sort' => $column, 'direction' => $newDirection] + request()->except(['sort', 'direction'])) }}" class="text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700 flex items-center space-x-1">
                                    <span>Name</span>
                                    @if($currentSort == $column)
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $currentDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left">
                                @php
                                    $column = 'email';
                                    $newDirection = ($currentSort == $column && $currentDirection == 'asc') ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ route('admin.users.index', ['sort' => $column, 'direction' => $newDirection] + request()->except(['sort', 'direction'])) }}" class="text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700 flex items-center space-x-1">
                                    <span>Email</span>
                                    @if($currentSort == $column)
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $currentDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee Code</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</span>
                            </th>
                            <th class="px-6 py-4 text-center">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined Date</span>
                            </th>
                            <th class="px-6 py-4 text-right">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $users->firstItem() + $loop->index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg shadow-blue-500/30">
                                        <span class="text-white text-sm font-bold">{{ substr($user->first_name ?? $user->name, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->job_title }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $user->email }}</p>
                                @if($user->work_email && $user->work_email !== $user->email)
                                    <p class="text-xs text-gray-500">{{ $user->work_email }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $user->employee_code ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $user->department->name ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $user->role->name ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">
                                    @if($user->phone_country_code && $user->phone_number)
                                        +{{ $user->phone_country_code }} {{ $user->phone_number }}
                                    @elseif($user->phone_number)
                                        {{ $user->phone_number }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->date_of_joining ? $user->date_of_joining->format('d M Y') : $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="toggleStatus('{{ Crypt::encrypt($user->id) }}', '{{ $user->is_active ? 'active' : 'inactive' }}', this)" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Change Status">
                                        @if($user->is_active)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </button>
                                    <a href="{{ route('admin.users.edit', Crypt::encrypt($user->id)) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.users.payroll', Crypt::encrypt($user->id)) }}" class="p-2 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Payroll">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">No users found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your search or filter</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 pagination-container">
                    <p class="pagination-info">
                        Showing {{ ($users->currentPage() - 1) * $users->perPage() + 1 }} to
                        {{ min($users->currentPage() * $users->perPage(), $users->total()) }}
                        of {{ $users->total() }} users
                    </p>

                    <div class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($users->onFirstPage())
                            <span class="page-item disabled">
                                <span class="page-link page-btn">&lsaquo;</span>
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="page-item">
                                <span class="page-link page-btn">&lsaquo;</span>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $users->lastPage(); $i++)
                            @if ($i == $users->currentPage())
                                <span class="page-item active">
                                    <span class="page-link">{{ $i }}</span>
                                </span>
                            @else
                                <a href="{{ $users->url($i) }}" class="page-item">
                                    <span class="page-link">{{ $i }}</span>
                                </a>
                            @endif
                        @endfor

                        {{-- Next Page Link --}}
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="page-item">
                                <span class="page-link page-btn">&rsaquo;</span>
                            </a>
                        @else
                            <span class="page-item disabled">
                                <span class="page-link page-btn">&rsaquo;</span>
                            </span>
                        @endif
                    </div>
                </div>
            @else
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-sm text-gray-500">Showing {{ $users->count() }} of {{ $users->total() }} users</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var searchTimeout;

    function toggleIcons() {
        var searchValue = $('#search-input').val();
        if (searchValue.length > 0) {
            $('.search-icon').addClass('hidden');
            $('.clear-icon').removeClass('hidden');
        } else {
            $('.search-icon').removeClass('hidden');
            $('.clear-icon').addClass('hidden');
        }
    }

    $('#search-input').on('keyup', function() {
        toggleIcons();
        clearTimeout(searchTimeout);
        var searchValue = $(this).val();
        searchTimeout = setTimeout(function() {
            // Show loading
            $('#loading-overlay').removeClass('hidden');
            // Submit the form
            $('#search-form').submit();
        }, 500); // 500ms delay
    });

    $('#search-button').on('click', function() {
        var searchValue = $('#search-input').val();
        if (searchValue.length > 0) {
            // Clear the input
            $('#search-input').val('');
            toggleIcons();
            // Submit the form to clear search
            $('#loading-overlay').removeClass('hidden');
            $('#search-form').submit();
        } else {
            // Focus the input
            $('#search-input').focus();
        }
    });

    // Initial toggle
    toggleIcons();

    function showFlashMessage(message, type) {
        var title = type === 'success' ? 'Success!' : 'Error!';
        var iconHtml = type === 'success' ?
            '<svg class="w-8 h-8 text-white animate-checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :
            '<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
        var bgClass = type === 'success' ? 'from-blue-500 to-indigo-600' : 'from-red-500 to-pink-600';
        var animateClass = type === 'success' ? 'animate-pulse' : 'animate-shake';

        var html = '<div id="custom-alert" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">' +
            '<div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-8 relative transform animate-bounce-in">' +
                '<div class="text-center">' +
                    '<div class="flex justify-center mb-4">' +
                        '<div class="w-16 h-16 bg-gradient-to-br ' + bgClass + ' rounded-full flex items-center justify-center ' + animateClass + '">' +
                            iconHtml +
                        '</div>' +
                    '</div>' +
                    '<h3 class="text-2xl font-bold text-gray-900 animate-slide-in mb-2">' + title + '</h3>' +
                    '<p class="text-gray-600 text-lg animate-slide-in-delay">' + message + '</p>' +
                </div>' +
                '<button onclick="closeAlert()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors animate-fade-in">' +
                    '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>' +
                '</button>' +
                '<div class="mt-6 bg-gray-200 rounded-full h-1">' +
                    '<div id="progress-bar" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-1 rounded-full animate-progress"></div>' +
                '</div>' +
            </div>' +
        '</div>';

        $('body').append(html);
        setTimeout(closeAlert, 3000);
    }

    function closeAlert() {
        const alert = document.getElementById('custom-alert');
        if (alert) {
            alert.classList.add('animate-fade-out');
            setTimeout(() => {
                alert.remove();
            }, 300);
        }
    }

    // Toggle status function
    window.toggleStatus = function(encryptedId, currentStatus, button) {
        var newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        $.ajax({
            url: '/admin/users/' + encryptedId + '/status',
            type: 'PUT',
            data: {
                status: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Update the icon
                var iconHtml = newStatus === 'active' ?
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                $(button).html(iconHtml);

                // Update the status badge in the row
                var row = $(button).closest('tr');
                var statusBadge = row.find('.inline-flex');
                if (newStatus === 'active') {
                    statusBadge.removeClass('bg-gray-100 text-gray-800').addClass('bg-green-100 text-green-800').html('Active');
                } else {
                    statusBadge.removeClass('bg-green-100 text-green-800').addClass('bg-gray-100 text-gray-800').html('Inactive');
                }

                // Show success message
                showFlashMessage(response.message, 'success');
            },
            error: function() {
                showFlashMessage('Failed to update status.', 'error');
            }
        });
    };

    // Hide loading on page load and refocus search input
    $(window).on('load', function() {
        $('#loading-overlay').addClass('hidden');
        var searchInput = $('#search-input');
        searchInput.focus();
        // Set cursor to the end
        var len = searchInput.val().length;
        searchInput[0].setSelectionRange(len, len);
        // Toggle icons
        toggleIcons();
    });
});
</script>

<style>
/* Flash Message Animations */
.animate-bounce-in {
    animation: bounceIn 0.6s ease-out;
}

.animate-checkmark {
    animation: checkmark 0.8s ease-in-out 0.2s both;
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

.animate-slide-in {
    animation: slideIn 0.5s ease-out 0.1s both;
}

.animate-slide-in-delay {
    animation: slideIn 0.5s ease-out 0.3s both;
}

.animate-progress {
    animation: progress 3s linear;
}

.animate-fade-out {
    animation: fadeOut 0.3s ease-in-out;
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes checkmark {
    0% {
        transform: scale(0) rotate(45deg);
        opacity: 0;
    }
    50% {
        transform: scale(1.2) rotate(45deg);
        opacity: 1;
    }
    100% {
        transform: scale(1) rotate(0deg);
        opacity: 1;
    }
}

@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    10%, 30%, 50%, 70%, 90% {
        transform: translateX(-5px);
    }
    20%, 40%, 60%, 80% {
        transform: translateX(5px);
    }
}

@keyframes slideIn {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes progress {
    0% {
        width: 100%;
    }
    100% {
        width: 0%;
    }
}

@keyframes fadeOut {
    0% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}

/* Old Style Pagination */
.pagination-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.pagination-info {
    font-size: 0.875rem;
    color: #6b7280;
}

.pagination {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.pagination .page-item {
    list-style: none;
}

.pagination .page-link,
.pagination .page-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 2rem;
    height: 2rem;
    padding: 0 0.5rem;
    border-radius: 0.375rem;
    border: 1px solid #d1d5db;
    background-color: #fff;
    color: #000000;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.15s ease;
    cursor: pointer;
}

.pagination .page-link:hover:not(:disabled) {
    background-color: #eff6ff;
    border-color: #3b82f6;
    color: #000000;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(to right, #3b82f6, #6366f1);
    border-color: transparent;
    color: #fff;
}

.pagination .page-item.disabled .page-link,
.pagination .page-item.disabled .page-btn {
    opacity: 0.5;
    cursor: not-allowed;
    background-color: #f9fafb;
}

.pagination .page-btn svg {
    width: 1rem;
    height: 1rem;
}
</style>

@endsection