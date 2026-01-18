@extends('layouts.app')

@section('title', 'Edit Payroll - ' . $user['name'])

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50 py-8">
    <div class="w-full px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Edit Payroll Section -->
            <div>
                <form action="{{ route('users.payroll.update', $user['id']) }}" method="POST" class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-300">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">Edit Payroll</h2>
                            <p class="text-sm text-gray-600">Update payroll information for {{ $user['name'] }}</p>
                        </div>

                        <div class="space-y-4">
                            <!-- Basic Salary -->
                            <div>
                                <label for="basic_salary" class="block text-sm font-medium text-gray-700 mb-2">Basic Salary</label>
                                <input type="number" id="basic_salary" name="basic_salary" value="{{ $user['payroll']['basic_salary'] ?? '' }}" placeholder="Enter basic salary" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" step="0.01">
                            </div>

                            <!-- HRA -->
                            <div>
                                <label for="hra" class="block text-sm font-medium text-gray-700 mb-2">HRA</label>
                                <input type="number" id="hra" name="hra" value="{{ $user['payroll']['hra'] ?? '' }}" placeholder="Enter HRA" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" step="0.01">
                            </div>

                            <!-- Conveyance -->
                            <div>
                                <label for="conveyance" class="block text-sm font-medium text-gray-700 mb-2">Conveyance Allowance</label>
                                <input type="number" id="conveyance" name="conveyance" value="{{ $user['payroll']['conveyance'] ?? '' }}" placeholder="Enter conveyance allowance" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" step="0.01">
                            </div>

                            <!-- Medical Allowance -->
                            <div>
                                <label for="medical" class="block text-sm font-medium text-gray-700 mb-2">Medical Allowance</label>
                                <input type="number" id="medical" name="medical" value="{{ $user['payroll']['medical'] ?? '' }}" placeholder="Enter medical allowance" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" step="0.01">
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('users.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                                Update Payroll
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Payroll History Section -->
            <div>
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-300">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Payroll History</h3>

                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @forelse($payrollHistory as $history)
                        <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($history['date'])->format('M d, Y') }}</span>
                                <span class="text-sm text-gray-500">{{ $history['updated_by'] }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Basic:</span>
                                    <span class="font-medium ml-1">${{ number_format($history['basic_salary'], 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">HRA:</span>
                                    <span class="font-medium ml-1">${{ number_format($history['hra'], 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Conveyance:</span>
                                    <span class="font-medium ml-1">${{ number_format($history['conveyance'], 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Medical:</span>
                                    <span class="font-medium ml-1">${{ number_format($history['medical'], 2) }}</span>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <span class="text-sm text-gray-600">Total:</span>
                                <span class="font-semibold text-lg ml-1">${{ number_format($history['total'], 2) }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <p>No payroll history available</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection