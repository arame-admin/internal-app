{{-- User Edit Form --}}
{{-- This page provides a form to edit an existing user with comprehensive validation and error display. --}}
{{-- Includes all user fields organized in logical sections with existing data populated. --}}

@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.users.index') }}" class="hover:text-blue-600">Users</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Edit User</li>
            </ol>
        </nav>

        <form action="{{ route('admin.users.update', Crypt::encrypt($user->id)) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        @csrf
        @method('PUT')

        <div class="space-y-8">
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" placeholder="John" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                        @error('first_name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" placeholder="Doe" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                        @error('last_name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Primary Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="john.doe@example.com" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                        @error('email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">This will be the login email</p>
                    </div>

                    <!-- Personal Email -->
                    <div>
                        <label for="personal_email" class="block text-sm font-medium text-gray-700 mb-2">Personal Email</label>
                        <input type="email" id="personal_email" name="personal_email" value="{{ old('personal_email', $user->personal_email) }}" placeholder="john.doe@gmail.com" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        @error('personal_email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="md:col-span-2">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <div class="flex">
                            <select name="phone_country_code" class="px-3 py-3 border border-r-0 border-gray-200 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                                <option value="">+XX</option>
                                <option value="1" {{ old('phone_country_code', $user->phone_country_code) == '1' ? 'selected' : '' }}>+1 (US)</option>
                                <option value="91" {{ old('phone_country_code', $user->phone_country_code) == '91' ? 'selected' : '' }}>+91 (IN)</option>
                                <option value="44" {{ old('phone_country_code', $user->phone_country_code) == '44' ? 'selected' : '' }}>+44 (UK)</option>
                                <option value="61" {{ old('phone_country_code', $user->phone_country_code) == '61' ? 'selected' : '' }}>+61 (AU)</option>
                            </select>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="1234567890" class="flex-1 px-4 py-3 border border-gray-200 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        @error('phone_number')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                        @error('phone_country_code')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Role -->
                    <div>
                        <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                        <select id="role_id" name="role_id" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Employee Code -->
                    <div>
                        <label for="employee_code" class="block text-sm font-medium text-gray-700 mb-2">Employee Code</label>
                        <input type="text" id="employee_code" name="employee_code" value="{{ old('employee_code', $user->employee_code) }}" placeholder="EMP001" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        @error('employee_code')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Work Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Work Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Job Title -->
                    <div>
                        <label for="job_title" class="block text-sm font-medium text-gray-700 mb-2">Job Title</label>
                        <input type="text" id="job_title" name="job_title" value="{{ old('job_title', $user->job_title) }}" placeholder="Software Engineer" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        @error('job_title')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Date of Joining -->
                    <div>
                        <label for="date_of_joining" class="block text-sm font-medium text-gray-700 mb-2">Date of Joining</label>
                        <input type="date" id="date_of_joining" name="date_of_joining" value="{{ old('date_of_joining', $user->date_of_joining ? $user->date_of_joining->format('Y-m-d') : '') }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        @error('date_of_joining')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <select id="department_id" name="department_id" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Designation -->
                    <div>
                        <label for="designation_id" class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                        <select id="designation_id" name="designation_id" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Select Designation</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}" {{ old('designation_id', $user->designation_id) == $designation->id ? 'selected' : '' }}>{{ $designation->name }}</option>
                            @endforeach
                        </select>
                        @error('designation_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Business Unit -->
                    <div>
                        <label for="bu_id" class="block text-sm font-medium text-gray-700 mb-2">Business Unit</label>
                        <select id="bu_id" name="bu_id" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Select Business Unit</option>
                            @foreach($businessUnits as $businessUnit)
                                <option value="{{ $businessUnit->id }}" {{ old('bu_id', $user->bu_id) == $businessUnit->id ? 'selected' : '' }}>{{ $businessUnit->name }}</option>
                            @endforeach
                        </select>
                        @error('bu_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location_id" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <select id="location_id" name="location_id" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Select Location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id', $user->location_id) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Work Email -->
                    <div>
                        <label for="work_email" class="block text-sm font-medium text-gray-700 mb-2">Work Email</label>
                        <input type="email" id="work_email" name="work_email" value="{{ old('work_email', $user->work_email) }}" placeholder="john.doe@company.com" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        @error('work_email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Work Number -->
                    <div>
                        <label for="work_number" class="block text-sm font-medium text-gray-700 mb-2">Work Number</label>
                        <input type="text" id="work_number" name="work_number" value="{{ old('work_number', $user->work_number) }}" placeholder="123-456-7890" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        @error('work_number')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Personal Details -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date of Birth -->
                    <div>
                        <label for="dob" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <input type="date" id="dob" name="dob" value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        @error('dob')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <select id="gender" name="gender" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Marital Status -->
                    <div>
                        <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-2">Marital Status</label>
                        <select id="marital_status" name="marital_status" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Select Status</option>
                            <option value="single" {{ old('marital_status', $user->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                            <option value="married" {{ old('marital_status', $user->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                            <option value="divorced" {{ old('marital_status', $user->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="widowed" {{ old('marital_status', $user->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                        @error('marital_status')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Marriage Date -->
                    <div>
                        <label for="marriage_date" class="block text-sm font-medium text-gray-700 mb-2">Marriage Date</label>
                        <input type="date" id="marriage_date" name="marriage_date" value="{{ old('marriage_date', $user->marriage_date ? $user->marriage_date->format('Y-m-d') : '') }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        @error('marriage_date')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Blood Group -->
                    <div>
                        <label for="blood_group" class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                        <select id="blood_group" name="blood_group" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Select Blood Group</option>
                            <option value="A+" {{ old('blood_group', $user->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_group', $user->blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_group', $user->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_group', $user->blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_group', $user->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_group', $user->blood_group) == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_group', $user->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_group', $user->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                        @error('blood_group')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nationality -->
                    <div>
                        <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">Nationality</label>
                        <input type="text" id="nationality" name="nationality" value="{{ old('nationality', $user->nationality) }}" placeholder="Indian" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        @error('nationality')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Physically Handicapped -->
                    <div class="md:col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="physically_handicapped" value="1" {{ old('physically_handicapped', $user->physically_handicapped) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Physically Handicapped</span>
                        </label>
                        @error('physically_handicapped')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                <div class="space-y-6">
                    <!-- About Me -->
                    <div>
                        <label for="about_me" class="block text-sm font-medium text-gray-700 mb-2">About Me</label>
                        <textarea id="about_me" name="about_me" rows="3" placeholder="Tell us about yourself..." class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">{{ old('about_me', $user->about_me) }}</textarea>
                        @error('about_me')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- What I Love About My Job -->
                    <div>
                        <label for="what_i_love_about_job" class="block text-sm font-medium text-gray-700 mb-2">What I Love About My Job</label>
                        <textarea id="what_i_love_about_job" name="what_i_love_about_job" rows="3" placeholder="What aspects of your job do you enjoy most..." class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">{{ old('what_i_love_about_job', $user->what_i_love_about_job) }}</textarea>
                        @error('what_i_love_about_job')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Address Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Current Address -->
                    <div>
                        <label for="current_address" class="block text-sm font-medium text-gray-700 mb-2">Current Address</label>
                        <textarea id="current_address" name="current_address" rows="3" placeholder="Current residential address..." class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">{{ old('current_address', $user->current_address) }}</textarea>
                        @error('current_address')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Permanent Address -->
                    <div>
                        <label for="permanent_address" class="block text-sm font-medium text-gray-700 mb-2">Permanent Address</label>
                        <textarea id="permanent_address" name="permanent_address" rows="3" placeholder="Permanent residential address..." class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">{{ old('permanent_address', $user->permanent_address) }}</textarea>
                        @error('permanent_address')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Residence Number -->
                    <div>
                        <label for="residence_number" class="block text-sm font-medium text-gray-700 mb-2">Residence Number</label>
                        <input type="text" id="residence_number" name="residence_number" value="{{ old('residence_number', $user->residence_number) }}" placeholder="Home phone number" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        @error('residence_number')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                Update User
            </button>
        </div>
    </form>
</div>
</div>

<script>
// Auto-generate full name
document.getElementById('first_name').addEventListener('input', updateFullName);
document.getElementById('last_name').addEventListener('input', updateFullName);

function updateFullName() {
    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    // Note: Full name is generated in the controller
}
</script>

@endsection