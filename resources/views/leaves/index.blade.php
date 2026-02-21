@extends('layouts.app')

@section('title', 'Leaves Management')

@section('content')

<!-- Leaves Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-6 py-4 text-left">
                    <a href="{{ route('admin.leaves.index', ['sort' => 'year']) }}" class="flex items-center space-x-1 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                        <span>Year</span>
                        @if(request('sort') == 'year')
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
                <th class="px-6 py-4 text-center">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sick Leaves</span>
                </th>
                <th class="px-6 py-4 text-center">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Casual Leaves</span>
                </th>
                <th class="px-6 py-4 text-center">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Earned Leaves</span>
                </th>
                <th class="px-6 py-4 text-center">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</span>
                </th>
                <th class="px-6 py-4 text-right">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</span>
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($leaves as $leave)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $leave['year'] }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ $leave['sick_leaves'] }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $leave['casual_leaves'] }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ $leave['earned_leaves'] }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($leave['status'] == 'active')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Inactive
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-2">
                        <button onclick="toggleStatus('{{ encrypt($leave['id']) }}', '{{ $leave['status'] }}', this)" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Change Status">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </button>
                        <a href="{{ route('admin.leaves.edit', encrypt($leave['id'])) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">No leaves found</p>
                        <p class="text-gray-400 text-sm mt-1">Try adjusting your search or filter</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    @if(isset($paginator) && $paginator->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 pagination-container">
            <p class="pagination-info">
                Showing {{ ($paginator->currentPage() - 1) * $paginator->perPage() + 1 }} to
                {{ min($paginator->currentPage() * $paginator->perPage(), $paginator->total()) }}
                of {{ $paginator->total() }} leaves
            </p>

            <div class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="page-item disabled">
                        <span class="page-link page-btn">&lsaquo;</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-item">
                        <span class="page-link page-btn">&lsaquo;</span>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                    @if ($i == $paginator->currentPage())
                        <span class="page-item active">
                            <span class="page-link">{{ $i }}</span>
                        </span>
                    @else
                        <a href="{{ $paginator->url($i) }}" class="page-item">
                            <span class="page-link">{{ $i }}</span>
                        </a>
                    @endif
                @endfor

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="page-item">
                        <span class="page-link page-btn">&rsaquo;</span>
                    </a>
                @else
                    <span class="page-item disabled">
                        <span class="page-link page-btn">&rsaquo;</span>
                    </span>
                @endif
            </div>
        </div>
    @elseif(isset($leaves))
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">Showing {{ count($leaves) }} of {{ count($leaves) }} leaves</p>
        </div>
    @endif
</div>

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
<a href="{{ route('admin.leaves.create') }}" class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-full p-4 shadow-lg hover:from-blue-600 hover:to-indigo-700 transition-colors z-50" title="Add New Leave">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
    </svg>
</a>

<script>
// Toggle status function
window.toggleStatus = function(encryptedId, currentStatus, button) {
    // Determine the new status
    var newStatus = currentStatus === 'active' ? 'inactive' : 'active';

    $.ajax({
        url: '/admin/leaves/' + encryptedId + '/status',
        type: 'PUT',
        data: {
            status: newStatus,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                // Update the status badge in the row (5th column - index 4)
                var row = $(button).closest('tr');
                var statusCell = row.find('td').eq(4); // Status is in 5th column (0-indexed: 4)
                var statusBadge = statusCell.find('.inline-flex');
                if (newStatus === 'active') {
                    statusBadge.removeClass('bg-gray-100 text-gray-800').addClass('bg-green-100 text-green-800').html('Active');
                } else {
                    statusBadge.removeClass('bg-green-100 text-green-800').addClass('bg-gray-100 text-gray-800').html('Inactive');
                }

                // Update button onclick with new status
                $(button).attr('onclick', 'toggleStatus(\'' + encryptedId + '\', \'' + newStatus + '\', this)');
            }
        }
    });
};
</script>
@endsection
