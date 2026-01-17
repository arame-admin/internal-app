@extends('layouts.app')

@section('title', 'Add New Role')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Role Form -->
    <div class="lg:col-span-2">
        <form action="#" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            @csrf
            
            <div class="space-y-6">
                <!-- Role Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Role Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter role name" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">The role name will be displayed to users</p>
                </div>
                
                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <input type="text" id="slug" name="slug" placeholder="role-slug" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-gray-50" readonly>
                    <p class="text-xs text-gray-500 mt-1">Auto-generated from role name</p>
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3" placeholder="Describe the purpose of this role" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"></textarea>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="active" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="inactive" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Inactive</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('roles.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                    Create Role
                </button>
            </div>
        </form>
    </div>
    
    <!-- Permissions Panel -->
    <div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Permissions</h3>
            
            <div class="space-y-4">
                <!-- Permission Group -->
                <div>
                    <label class="flex items-center space-x-3 py-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Dashboard</span>
                    </label>
                    <div class="ml-7 space-y-2 mt-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="dashboard.view">
                            <span class="text-sm text-gray-600">View Dashboard</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="dashboard.stats">
                            <span class="text-sm text-gray-600">View Stats</span>
                        </label>
                    </div>
                </div>
                
                <!-- Permission Group -->
                <div class="border-t border-gray-100 pt-4">
                    <label class="flex items-center space-x-3 py-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Timesheet</span>
                    </label>
                    <div class="ml-7 space-y-2 mt-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="timesheet.view">
                            <span class="text-sm text-gray-600">View Timesheet</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="timesheet.create">
                            <span class="text-sm text-gray-600">Create Entry</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="timesheet.approve">
                            <span class="text-sm text-gray-600">Approve Timesheet</span>
                        </label>
                    </div>
                </div>
                
                <!-- Permission Group -->
                <div class="border-t border-gray-100 pt-4">
                    <label class="flex items-center space-x-3 py-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Leave Management</span>
                    </label>
                    <div class="ml-7 space-y-2 mt-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="leave.view">
                            <span class="text-sm text-gray-600">View Leave</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="leave.request">
                            <span class="text-sm text-gray-600">Request Leave</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="leave.approve">
                            <span class="text-sm text-gray-600">Approve Leave</span>
                        </label>
                    </div>
                </div>
                
                <!-- Permission Group -->
                <div class="border-t border-gray-100 pt-4">
                    <label class="flex items-center space-x-3 py-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Users</span>
                    </label>
                    <div class="ml-7 space-y-2 mt-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="users.view">
                            <span class="text-sm text-gray-600">View Users</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="users.create">
                            <span class="text-sm text-gray-600">Create User</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="users.edit">
                            <span class="text-sm text-gray-600">Edit User</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="users.delete">
                            <span class="text-sm text-gray-600">Delete User</span>
                        </label>
                    </div>
                </div>
                
                <!-- Permission Group -->
                <div class="border-t border-gray-100 pt-4">
                    <label class="flex items-center space-x-3 py-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Roles</span>
                    </label>
                    <div class="ml-7 space-y-2 mt-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="roles.view">
                            <span class="text-sm text-gray-600">View Roles</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="roles.create">
                            <span class="text-sm text-gray-600">Create Role</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="roles.edit">
                            <span class="text-sm text-gray-600">Edit Role</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="permissions[]" value="roles.delete">
                            <span class="text-sm text-gray-600">Delete Role</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const slug = this.value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');
    document.getElementById('slug').value = slug;
});
</script>
@endpush
@endsection

