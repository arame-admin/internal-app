@extends('layouts.app')

@section('title', 'Users Management')

@section('content')

<!-- Filters & Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
    <div class="p-4 border-b border-gray-100">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Search -->
            <div class="relative flex-1 max-w-md">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            </div>
            
            <!-- Filters -->
            <div class="flex items-center space-x-3">
                <select name="department" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>{{ $department }}</option>
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
                    <option value="department" {{ request('sort') == 'department' ? 'selected' : '' }}>Department</option>
                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Joined Date</option>
                </select>
                
                <button type="submit" class="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </button>
                
                @if(request()->anyFilled(['search', 'department', 'status', 'sort']))
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm text-red-600 hover:text-red-700">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-6 py-4 text-left">
                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </th>
                <th class="px-6 py-4 text-left">
                    <a href="{{ route('admin.users.index', ['sort' => 'name', 'search' => request('search'), 'department' => request('department'), 'status' => request('status')]) }}" class="flex items-center space-x-1 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                        <span>User</span>
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
                    <a href="{{ route('admin.users.index', ['sort' => 'email', 'search' => request('search'), 'department' => request('department'), 'status' => request('status')]) }}" class="flex items-center space-x-1 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                        <span>Email</span>
                        @if(request('sort') == 'email')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            </svg>
                        @endif
                    </a>
                </th>
                <th class="px-6 py-4 text-left">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</span>
                </th>
                <th class="px-6 py-4 text-left">
                    <a href="{{ route('admin.users.index', ['sort' => 'department', 'search' => request('search'), 'department' => request('department'), 'status' => request('status')]) }}" class="flex items-center space-x-1 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                        <span>Department</span>
                        @if(request('sort') == 'department')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            </svg>
                        @endif
                    </a>
                </th>
                <th class="px-6 py-4 text-left">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</span>
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
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ substr($user['name'], 0, 2) }}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $user['name'] }}</p>
                            <p class="text-xs text-gray-500">Joined: {{ $user['joined_date'] }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-600">{{ $user['email'] }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $user['role'] }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-600">{{ $user['department'] }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-600">{{ $user['phone'] }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($user['status'] == 'active')
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
                        <a href="{{ route('admin.users.status', $user['id']) }}" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Change Status">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.users.edit', $user['id']) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.users.payroll', $user['id']) }}" class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Edit Payroll">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </a>
                        <form action="{{ route('admin.users.destroy', $user['id']) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete" onclick="return confirm('Are you sure you want to delete this user?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">No users found</p>
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
                of {{ $paginator->total() }} users
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
    @elseif(isset($users))
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">Showing {{ count($users) }} of {{ count($users) }} users</p>
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
<a href="{{ route('admin.users.create') }}" class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-full p-4 shadow-lg hover:from-blue-600 hover:to-indigo-700 transition-colors z-50" title="Add New User">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
    </svg>
</a>
@endsection

