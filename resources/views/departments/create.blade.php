@extends('layouts.app')

@section('title', 'Add New Department')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.departments.index') }}" class="hover:text-blue-600">Departments</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Add Department</li>
            </ol>
        </nav>

        <form action="{{ route('admin.departments.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        @csrf

        <div class="space-y-6">
            <!-- Department Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Department Name</label>
                <input type="text" id="name" name="name" placeholder="e.g., Information Technology" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                <span id="name-error" class="text-red-500 text-sm mt-1 hidden"></span>
                <p class="text-xs text-gray-500 mt-1">A descriptive name for the department</p>
            </div>

            <!-- Department Code -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Department Code</label>
                <input type="text" id="code" name="code" placeholder="e.g., IT" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                <span id="code-error" class="text-red-500 text-sm mt-1 hidden"></span>
                <p class="text-xs text-gray-500 mt-1">Unique code identifier (e.g., IT, HR, FIN)</p>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Optional description of the department..." class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"></textarea>
                <p class="text-xs text-gray-500 mt-1">Optional description (max 500 characters)</p>
            </div>

        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.departments.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                Create Department
            </button>
        </div>
    </form>
</div>
</div>

<script>
function showError(elementId, message) {
    const el = document.getElementById(elementId);
    el.textContent = message;
    el.classList.remove('hidden');
}

function hideError(elementId) {
    const el = document.getElementById(elementId);
    el.classList.add('hidden');
}

document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const code = document.getElementById('code').value.trim();

    hideError('name-error');
    hideError('code-error');

    let hasError = false;

    if (!name) {
        showError('name-error', 'Department name is required.');
        hasError = true;
    }

    if (!code) {
        showError('code-error', 'Department code is required.');
        hasError = true;
    } else if (code.length > 10) {
        showError('code-error', 'Department code must be 10 characters or less.');
        hasError = true;
    } else if (!/^[A-Z0-9]+$/.test(code)) {
        showError('code-error', 'Department code must contain only uppercase letters and numbers.');
        hasError = true;
    }

    if (hasError) {
        e.preventDefault();
    }
});
</script>
@endsection