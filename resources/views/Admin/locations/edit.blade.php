{{-- Location Edit Form --}}
{{-- This page provides a form to edit an existing location with validation and error display. --}}
{{-- Includes server-side validation for name and code uniqueness, excluding the current record. --}}

@extends('layouts.app')

@section('title', 'Edit Location')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.locations.index') }}" class="hover:text-blue-600">Locations</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Edit Location</li>
            </ol>
        </nav>

        <form action="{{ route('admin.locations.update', Crypt::encrypt($location->id)) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Location Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Location Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $location->name) }}" placeholder="e.g., New York Office" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                @error('name')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
                <p class="text-xs text-gray-500 mt-1">A descriptive name for the location</p>
            </div>

            <!-- Location Code -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Location Code</label>
                <input type="text" id="code" name="code" value="{{ old('code', $location->code) }}" placeholder="e.g., NYO" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                @error('code')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Unique code identifier (cannot be changed)</p>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea id="address" name="address" rows="3" placeholder="Street address..." class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">{{ $location->address }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Full street address (optional)</p>
            </div>

            <!-- City, State, Country Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" id="city" name="city" value="{{ old('city', $location->city) }}" placeholder="e.g., New York" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    <p class="text-xs text-gray-500 mt-1">City name (optional)</p>
                </div>

                <!-- State -->
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State/Province</label>
                    <input type="text" id="state" name="state" value="{{ old('state', $location->state) }}" placeholder="e.g., NY" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    <p class="text-xs text-gray-500 mt-1">State or province (optional)</p>
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                    <input type="text" id="country" name="country" value="{{ old('country', $location->country) }}" placeholder="e.g., USA" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    <p class="text-xs text-gray-500 mt-1">Country name (optional)</p>
                </div>
            </div>

            <!-- Postal Code -->
            <div>
                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $location->postal_code) }}" placeholder="e.g., 10001" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-500 mt-1">ZIP or postal code (optional)</p>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Optional description of the location..." class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">{{ $location->description }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Optional description (max 500 characters)</p>
            </div>

        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.locations.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                Update Location
            </button>
        </div>
    </form>
    </div>
</div>
@endsection