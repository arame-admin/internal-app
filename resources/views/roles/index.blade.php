@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')

<!-- Filters & Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
    <div class="p-4 border-b border-gray-100">
        <form action="{{ route('admin.roles.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Search -->
            <div class="relative flex-1 max-w-md">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search roles..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            </div>
            
            <!-- Filters -->
            <div class="flex items-center space-x-3">
                <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>

                <select name="sort" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white">
                    <option value="">Sort By</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Created Date</option>
                </select>
                
                <button type="submit" class="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </button>
                
                @if(request()->anyFilled(['search', 'status', 'sort']))
                    <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 text-sm text-red-600 hover:text-red-700">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Roles Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-6 py-4 text-left">
                    <a href="{{ route('admin.roles.index', ['sort' => 'name', 'search' => request('search'), 'status' => request('status')]) }}" class="flex items-center space-x-1 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                        <span>Role Name</span>
                        @if(request('sort') == 'name')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        @endif
                    </a>
                </th>
                <th class="px-6 py-4 text-left">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</span>
                </th>
                <th class="px-6 py-4 text-center">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</span>
                </th>
                <th class="px-6 py-4 text-center">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Created At</span>
                </th>
                <th class="px-6 py-4 text-center">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Updated At</span>
                </th>
                <th class="px-6 py-4 text-right">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</span>
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($roles as $role)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $role->name }}</p>
                            <p class="text-xs text-gray-500">{{ $role->name }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-600 max-w-xs truncate">{{ $role->description }}</p>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($role->status == 'active' || $role->status == true || $role->status == 1)
                        <span id="status-badge-{{ $role->id }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @else
                        <span id="status-badge-{{ $role->id }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Inactive
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-sm text-gray-600">{{ $role->created_at->format('M d, Y') }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-sm text-gray-600">{{ $role->updated_at->format('M d, Y') }}</span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-2">
                        <button onclick="toggleStatus({{ $role->id }}, {{ $role->status ? 1 : 0 }})" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Toggle Status" id="status-btn-{{ $role->id }}">
                            @if($role->status == 'active' || $role->status == true || $role->status == 1)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </button>
                            @if($role->status == 'active' || $role->status == true || $role->status == 1)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </button>
                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">No roles found</p>
                        <p class="text-gray-400 text-sm mt-1">Try adjusting your search or filter</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Pagination -->
    @if($roles->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 pagination-container">
            <p class="pagination-info">
                Showing {{ ($roles->currentPage() - 1) * $roles->perPage() + 1 }} to
                {{ min($roles->currentPage() * $roles->perPage(), $roles->total()) }}
                of {{ $roles->total() }} roles
            </p>

            <div class="pagination">
                {{-- Previous Page Link --}}
                @if ($roles->onFirstPage())
                    <span class="page-item disabled">
                        <span class="page-link page-btn">&lsaquo;</span>
                    </span>
                @else
                    <a href="{{ $roles->previousPageUrl() }}" class="page-item">
                        <span class="page-link page-btn">&lsaquo;</span>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @for ($i = 1; $i <= $roles->lastPage(); $i++)
                    @if ($i == $roles->currentPage())
                        <span class="page-item active">
                            <span class="page-link">{{ $i }}</span>
                        </span>
                    @else
                        <a href="{{ $roles->url($i) }}" class="page-item">
                            <span class="page-link">{{ $i }}</span>
                        </a>
                    @endif
                @endfor

                {{-- Next Page Link --}}
                @if ($roles->hasMorePages())
                    <a href="{{ $roles->nextPageUrl() }}" class="page-item">
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
            <p class="text-sm text-gray-500">Showing {{ $roles->count() }} of {{ $roles->total() }} roles</p>
        </div>
    @endif
</div>

<script>
function toggleStatus(id, currentStatus) {
    var newStatus = currentStatus ? 0 : 1;
    $.ajax({
        url: '{{ url("admin/roles") }}/' + id + '/status',
        type: 'PUT',
        data: {
            status: newStatus,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            // Update the status badge
            var statusBadge = $('#status-badge-' + id);
            if (newStatus == 1) {
                statusBadge.removeClass('bg-gray-100 text-gray-800').addClass('bg-green-100 text-green-800').html('Active');
            } else {
                statusBadge.removeClass('bg-green-100 text-green-800').addClass('bg-gray-100 text-gray-800').html('Inactive');
            }

            // Update the button icon
            var btn = $('#status-btn-' + id);
            if (newStatus == 1) {
                btn.html('<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>');
            } else {
                btn.html('<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>');
            }

            // Show success message
            showFlashMessage('Status updated successfully.', 'success');
        },
        error: function() {
            showFlashMessage('Failed to update status.', 'error');
        }
    });
}

function showFlashMessage(message, type) {
    var colorClass = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    var html = '<div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 animate-fade-in"><div class="' + colorClass + ' text-white px-6 py-4 rounded-lg shadow-lg border max-w-md"><p class="font-medium">' + message + '</p></div></div>';
    $('body').append(html);
    setTimeout(function() {
        $('.animate-fade-in').remove();
    }, 3000);
}
</script>

<style>
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
    color: #374151;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.15s ease;
    cursor: pointer;
}

.pagination .page-link:hover:not(:disabled) {
    background-color: #f3f4f6;
    border-color: #9ca3af;
    color: #1f2937;
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

.pagination-gap {
    color: #9ca3af;
    padding: 0 0.25rem;
}

/* Simple Page Numbers Style */
.page-numbers {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}
</style>

<!-- Floating Add Button -->
<a href="{{ route('roles.create') }}" class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-full p-4 shadow-lg hover:from-blue-600 hover:to-indigo-700 transition-colors z-50" title="Add New Role">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
    </svg>
</a>
@endsection

