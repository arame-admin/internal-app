{{-- Designations Index Page --}}
{{-- This page displays a list of designations with search, filter, sort, and pagination functionality. --}}
{{-- It includes AJAX status toggling and responsive design. --}}

@extends('layouts.app')

@section('title', 'Designations')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.designations.index') }}" class="hover:text-blue-600">Designations</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">List</li>
            </ol>
        </nav>

        <!-- Floating Add Button -->
        <a href="{{ route('admin.designations.create') }}" class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-full p-4 shadow-lg hover:from-blue-600 hover:to-indigo-700 transition-colors z-50" title="Add New Designation">
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
                <form action="{{ route('admin.designations.index') }}" method="GET" id="search-form" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Search -->
                    <div class="relative flex-1 max-w-md">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search designations..." class="w-full pl-10 pr-12 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" id="search-input">
                        <button type="button" id="search-button" class="absolute right-3 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-4 h-4 search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <svg class="w-4 h-4 clear-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
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
                                <a href="{{ route('admin.designations.index', ['sort' => $column, 'direction' => $newDirection] + request()->except(['sort', 'direction'])) }}" class="text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700 flex items-center space-x-1">
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
                                    $column = 'code';
                                    $newDirection = ($currentSort == $column && $currentDirection == 'asc') ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ route('admin.designations.index', ['sort' => $column, 'direction' => $newDirection] + request()->except(['sort', 'direction'])) }}" class="text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700 flex items-center space-x-1">
                                    <span>Code</span>
                                    @if($currentSort == $column)
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $currentDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                @php
                                    $column = 'status';
                                    $newDirection = ($currentSort == $column && $currentDirection == 'asc') ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ route('admin.designations.index', ['sort' => $column, 'direction' => $newDirection] + request()->except(['sort', 'direction'])) }}" class="text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700 flex items-center space-x-1">
                                    <span>Status</span>
                                    @if($currentSort == $column)
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $currentDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left">
                                @php
                                    $column = 'created_at';
                                    $newDirection = ($currentSort == $column && $currentDirection == 'asc') ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ route('admin.designations.index', ['sort' => $column, 'direction' => $newDirection] + request()->except(['sort', 'direction'])) }}" class="text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700 flex items-center space-x-1">
                                    <span>Created Date</span>
                                    @if($currentSort == $column)
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $currentDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($designations as $index => $designation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ($designations->currentPage() - 1) * $designations->perPage() + $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $designation->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $designation->code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $designation->department->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $designation->description }}">
                                        {{ $designation->description ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $designation->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($designation->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $designation->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button onclick="toggleStatus('{{ Crypt::encrypt($designation->id) }}', '{{ $designation->status }}', this)" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Change Status">
                                            @if($designation->status == 'active')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                        </button>
                                        <a href="{{ route('admin.designations.edit', Crypt::encrypt($designation->id)) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-.98-5.5-2.5m-.5-4a7.963 7.963 0 014-1.5c2.34 0 4.29.98 5.5 2.5m.5 4a7.963 7.963 0 01-4 1.5"></path>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">No designations found</h3>
                                        <p class="text-gray-500 mb-4">Get started by creating your first designation.</p>
                                        <a href="{{ route('admin.designations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Add Designation
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($designations->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing {{ $designations->firstItem() }} to {{ $designations->lastItem() }} of {{ $designations->total() }} results
                        </div>
                        <div class="flex space-x-1">
                            {{ $designations->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Status Toggle Modal -->
<div id="status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modal-title">Change Status</h3>
            <form id="status-form" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status-select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason (Optional)</label>
                    <textarea name="reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter reason for status change..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Update Status</button>
                </div>
            </form>
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
                '</div>' +
                '<button onclick="closeAlert()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors animate-fade-in">' +
                    '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>' +
                '</button>' +
                '<div class="mt-6 bg-gray-200 rounded-full h-1">' +
                    '<div id="progress-bar" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-1 rounded-full animate-progress"></div>' +
                '</div>' +
            '</div>' +
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
            url: '/admin/designations/' + encryptedId + '/status',
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
                    statusBadge.removeClass('bg-red-100 text-red-800').addClass('bg-green-100 text-green-800').html('Active');
                } else {
                    statusBadge.removeClass('bg-green-100 text-green-800').addClass('bg-red-100 text-red-800').html('Inactive');
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
        transform: scale(1);
    }
    100% {
        opacity: 0;
        transform: scale(0.95);
    }
}
</style>
@endsection