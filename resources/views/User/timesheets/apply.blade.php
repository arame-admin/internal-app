@extends('layouts.app')

@section('title', 'Log Timesheet - ' . date('F Y', mktime(0, 0, 0, $month, 1, $year)))

@section('content')
@php
    $userRole = auth()->user()->role_id ?? 0;
    $timesheetRoutePrefix = $userRole == 2 ? 'manager.' : 'employee.';
    $selectedMonthName = date('F Y', mktime(0, 0, 0, $month, 1, $year));
    
    // Calculate previous and next month links
    $prevMonth = $month == 1 ? 12 : $month - 1;
    $prevYear = $month == 1 ? $year - 1 : $year;
    $nextMonth = $month == 12 ? 1 : $month + 1;
    $nextYear = $month == 12 ? $year + 1 : $year;
    
    // Check if next month is in the future (don't allow future months)
    $now = now();
    $isNextMonthAllowed = ($nextYear < $now->year) || ($nextYear == $now->year && $nextMonth <= $now->month);
@endphp
<div class="p-6 mt-16">
    <div class="max-w-4xl mx-auto">
        <!-- Month Navigation -->
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route($timesheetRoutePrefix . 'timesheets.apply', ['year' => $prevYear, 'month' => $prevMonth]) }}" 
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                {{ date('M Y', mktime(0, 0, 0, $prevMonth, 1, $prevYear)) }}
            </a>
            <h2 class="text-xl font-bold text-gray-800">{{ $selectedMonthName }}</h2>
            @if($isNextMonthAllowed)
            <a href="{{ route($timesheetRoutePrefix . 'timesheets.apply', ['year' => $nextYear, 'month' => $nextMonth]) }}" 
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 flex items-center gap-1">
                {{ date('M Y', mktime(0, 0, 0, $nextMonth, 1, $nextYear)) }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            @else
            <span class="px-4 py-2 text-gray-400">Current</span>
            @endif
        </div>
        
        <!-- Back to list link -->
        <div class="mb-4">
            <a href="{{ route($timesheetRoutePrefix . 'timesheets.index', ['year' => $year, 'month' => $month]) }}" 
               class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to {{ $selectedMonthName }} Timesheets
            </a>
        </div>
        <!-- Monthly & Weekly Total -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">This Month</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($monthlyTotal, 2) }} <span class="text-sm font-normal">/ 160 hrs</span></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">This Week</p>
                        <p class="text-2xl font-bold {{ $weeklyTotal >= 40 ? 'text-green-600' : 'text-yellow-600' }}">{{ number_format($weeklyTotal, 2) }} <span class="text-sm font-normal">/ 40 hrs</span></p>
                        @if($weeklyTotal < 40 && $weeklyTotal > 0)
                            <p class="text-xs text-red-500 mt-1">{{ number_format(40 - $weeklyTotal, 2) }} hrs remaining</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Log New Entry Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Log Hours</h2>
            
            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-600">{{ session('success') }}</p>
                </div>
            @endif
            
            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-600">{{ session('error') }}</p>
                </div>
            @endif
            
            <form action="{{ route($timesheetRoutePrefix . 'timesheets.store') }}" method="POST" id="timesheetForm">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="month" value="{{ $month }}">
                <div class="space-y-4">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                        <input type="date" id="date" name="date" required max="{{ now()->format('Y-m-d') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Time Entries Container -->
                    <div id="time-entries-container" class="space-y-4">
                        <!-- Initial time entry -->
                        <div class="time-entry p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-medium text-gray-700">Entry #1</span>
                                <button type="button" class="remove-entry text-red-500 hover:text-red-700 text-sm hidden">Remove</button>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Time <span class="text-red-500">*</span></label>
                                    <input type="time" name="entries[0][start_time]" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">End Time <span class="text-red-500">*</span></label>
                                    <input type="time" name="entries[0][end_time]" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Project <span class="text-red-500">*</span></label>
                                    <select name="entries[0][project_id]" class="project-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">Select project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" data-project-dept-id="{{ $project->project_department_id }}" data-tasks="{{ json_encode($project->tasks ?? []) }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Task <span class="text-red-500">*</span></label>
                                    <select id="task-0" name="entries[0][task]" class="task-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select project first</option>
                                    </select>
                                    <input type="text" name="entries[0][task_text]" class="task-text-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 hidden" placeholder="Enter custom task">
                                    <button type="button" class="toggle-task-mode mt-1 text-xs text-blue-600 hover:text-blue-800 font-medium">Enter custom task instead</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Another Entry Button -->
                    <button type="button" id="add-entry" class="w-full py-2 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 transition-colors">
                        + Add Another Entry
                    </button>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                        <textarea id="description" name="description" rows="2" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="What did you work on today? (optional)"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route($timesheetRoutePrefix . 'timesheets.index') }}" class="px-5 py-2.5 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Log Hours
                    </button>
                </div>
            </form>

            <script>
                // User's department tasks from available_tasks
                let userDepartmentTasks = [];
                try {
                    const deptTasksRaw = '{!! addslashes(json_encode($userDepartmentTasks ?? [])) !!}';
                    userDepartmentTasks = JSON.parse(deptTasksRaw);
                } catch(e) {
                    console.error('Error parsing department tasks:', e);
                }

                let entryCounter = 0;

                document.addEventListener('DOMContentLoaded', function() {
                    const container = document.getElementById('time-entries-container');
                    const addButton = document.getElementById('add-entry');

                    // Initialize first entry's project/task handlers
                    initProjectTaskHandlers(container.querySelector('.time-entry'));

                    addButton.addEventListener('click', function() {
                        entryCounter++;
                        const entryHtml = createTimeEntry(entryCounter);
                        container.insertAdjacentHTML('beforeend', entryHtml);
                        
                        // Initialize handlers for new entry
                        const newEntry = container.lastElementChild;
                        initProjectTaskHandlers(newEntry);
                        
                        updateRemoveButtons();
                    });

                    function createTimeEntry(index) {
                        const projectsOptions = `@foreach($projects as $project)
                            <option value="{{ $project->id }}" data-project-dept-id="{{ $project->project_department_id }}" data-tasks="{{ json_encode($project->tasks ?? []) }}">{{ $project->name }}</option>
                        @endforeach`;
                        
                        return `
                            <div class="time-entry p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm font-medium text-gray-700">Entry #${index + 1}</span>
                                    <button type="button" class="remove-entry text-red-500 hover:text-red-700 text-sm">Remove</button>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Time <span class="text-red-500">*</span></label>
                                        <input type="time" name="entries[${index}][start_time]" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">End Time <span class="text-red-500">*</span></label>
                                        <input type="time" name="entries[${index}][end_time]" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Project <span class="text-red-500">*</span></label>
                                        <select name="entries[${index}][project_id]" class="project-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                            <option value="">Select project</option>
                                            ${projectsOptions}
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Task <span class="text-red-500">*</span></label>
                                        <select name="entries[${index}][task]" class="task-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select project first</option>
                                        </select>
                                        <input type="text" name="entries[${index}][task_text]" class="task-text-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 hidden" placeholder="Enter custom task">
                                        <button type="button" class="toggle-task-mode mt-1 text-xs text-blue-600 hover:text-blue-800 font-medium">Enter custom task instead</button>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    function initProjectTaskHandlers(entry) {
                        const projectSelect = entry.querySelector('.project-select');
                        const taskSelect = entry.querySelector('.task-select');
                        const taskText = entry.querySelector('.task-text-input');
                        const toggleBtn = entry.querySelector('.toggle-task-mode');
                        const removeBtn = entry.querySelector('.remove-entry');

                        // Initialize task select as disabled until project is selected
                        if (taskSelect) {
                            taskSelect.disabled = true;
                        }

                        if (projectSelect) {
                            projectSelect.addEventListener('change', function() {
                                const option = this.selectedOptions[0];
                                let projectTasks = JSON.parse(option?.dataset?.tasks || '[]');
                                
                                if (!projectTasks || projectTasks.length === 0) {
                                    if (userDepartmentTasks && userDepartmentTasks.length > 0) {
                                        projectTasks = userDepartmentTasks;
                                    } else {
                                        projectTasks = ['General Work', 'Meeting', 'Documentation', 'UI/UX', 'Coding', 'Testing', 'DevOps', 'Project Meeting'];
                                    }
                                }
                                
                                taskSelect.innerHTML = '<option value="">Select predefined task</option>';
                                taskSelect.disabled = false;
                                taskSelect.classList.remove('hidden');
                                taskText.classList.add('hidden');
                                taskText.value = '';
                                taskSelect.required = true;
                                taskText.required = false;
                                taskSelect.name = `entries[${getEntryIndex(entry)}][task]`;
                                taskText.name = `entries[${getEntryIndex(entry)}][task_text]`;
                                
                                projectTasks.forEach((task) => {
                                    const opt = document.createElement('option');
                                    opt.value = task;
                                    opt.textContent = task;
                                    taskSelect.appendChild(opt);
                                });
                            });
                        }

                        if (toggleBtn) {
                            let isUsingPredefined = true;
                            toggleBtn.addEventListener('click', function() {
                                isUsingPredefined = !isUsingPredefined;
                                const idx = getEntryIndex(entry);
                                
                                if (isUsingPredefined) {
                                    taskSelect.classList.remove('hidden');
                                    taskText.classList.add('hidden');
                                    taskText.required = false;
                                    taskText.name = `entries[${idx}][task_text]`;
                                    taskSelect.required = true;
                                    taskSelect.name = `entries[${idx}][task]`;
                                    toggleBtn.textContent = 'Enter custom task instead';
                                } else {
                                    taskSelect.classList.add('hidden');
                                    taskText.classList.remove('hidden');
                                    taskText.required = true;
                                    taskText.name = `entries[${idx}][task]`;
                                    taskSelect.required = false;
                                    taskSelect.name = `entries[${idx}][task_disabled]`;
                                    toggleBtn.textContent = 'Use predefined tasks';
                                }
                            });
                        }

                        if (removeBtn) {
                            removeBtn.addEventListener('click', function() {
                                entry.remove();
                                updateEntryNumbers();
                                updateRemoveButtons();
                            });
                        }
                    }

                    function getEntryIndex(entry) {
                        const entries = document.querySelectorAll('.time-entry');
                        for (let i = 0; i < entries.length; i++) {
                            if (entries[i] === entry) return i;
                        }
                        return 0;
                    }

                    function updateEntryNumbers() {
                        const entries = document.querySelectorAll('.time-entry');
                        entries.forEach((entry, index) => {
                            const label = entry.querySelector('.text-sm.font-medium');
                            if (label) label.textContent = `Entry #${index + 1}`;
                        });
                    }

                    function updateRemoveButtons() {
                        const entries = document.querySelectorAll('.time-entry');
                        const removeButtons = document.querySelectorAll('.remove-entry');
                        removeButtons.forEach(btn => {
                            btn.classList.toggle('hidden', entries.length <= 1);
                        });
                    }
                });
            </script>
        </div>

        <!-- Existing Entries -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Draft / Pending / Rejected Entries</h3>
            </div>
            @if($existing->isEmpty())
                <div class="p-8 text-center text-gray-500">No editable entries. All approved entries shown in list.</div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($groupedExisting as $groupKey => $entries)
                        @php
                            $firstEntry = $entries->first();
                            $batchId = $firstEntry->batch_id;
                            $totalHours = $entries->sum('hours');
                            $allDraft = $entries->every(function($e) { return $e->status == 'draft'; });
                            $allRejected = $entries->every(function($e) { return $e->status == 'rejected'; });
                            $allPending = $entries->every(function($e) { return $e->status == 'pending'; });
                            $mixedStatus = !$allDraft && !$allRejected && !$allPending;
                        @endphp
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $firstEntry->date->format('M d, Y') }}</p>
                                    <p class="text-sm text-gray-500">
                                        @foreach($entries as $entry)
                                            @if($entry->start_time && $entry->end_time)
                                                <span class="inline-block bg-gray-100 rounded px-2 py-1 mr-1 mb-1">
                                                    {{ $entry->start_time }} - {{ $entry->end_time }} ({{ $entry->task ?? 'N/A' }})
                                                </span>
                                            @endif
                                        @endforeach
                                        = {{ number_format($totalHours, 2) }} hrs total
                                    </p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs {{ match($firstEntry->status) { 'draft' => 'bg-gray-100 text-gray-700', 'pending' => 'bg-yellow-100 text-yellow-700', 'rejected' => 'bg-red-100 text-red-700' } }}">
                                    {{ ucfirst($firstEntry->status) }} ({{ $entries->count() }} entries)
                                </span>
                            </div>
                            @if($firstEntry->rejection_reason)
                                <div class="mb-3 p-2 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-700"><strong>Rejection Reason:</strong> {{ $firstEntry->rejection_reason }}</p>
                                </div>
                            @endif
                            @if($firstEntry->description)
                                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($firstEntry->description, 100) }}</p>
                            @endif
                            
                            <!-- Show individual entries for editing (only for draft/rejected) -->
                            @if($firstEntry->status == 'draft' || $firstEntry->status == 'rejected')
                                <div class="mb-3 space-y-2">
                                    @foreach($entries as $entry)
                                        <form action="{{ route($timesheetRoutePrefix . 'timesheets.updateDraft', $entry->id) }}" method="POST" class="inline-flex flex-wrap items-center gap-2">
                                            @csrf @method('PATCH')
                                            <input type="time" name="start_time" value="{{ $entry->start_time }}" class="px-2 py-1 border rounded text-sm" required>
                                            <span class="text-gray-500">to</span>
                                            <input type="time" name="end_time" value="{{ $entry->end_time }}" class="px-2 py-1 border rounded text-sm" required>
                                            <select name="project_id" class="px-2 py-1 border rounded text-sm" required>
                                                <option value="">Project</option>
                                                @foreach($projects as $project)
                                                    <option value="{{ $project->id }}" {{ $entry->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" name="task" value="{{ $entry->task }}" class="px-2 py-1 border rounded text-sm w-24" required placeholder="Task">
                                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Update</button>
                                        </form>
                                    @endforeach
                                </div>
                                
                                <!-- Batch actions: Submit all or Delete all -->
                                @if($firstEntry->status == 'draft')
                                    <form action="{{ route($timesheetRoutePrefix . 'timesheets.submit', $firstEntry->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-1.5 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">Submit All for Approval</button>
                                    </form>
                                @endif
                                <form action="{{ route($timesheetRoutePrefix . 'timesheets.destroy', $firstEntry->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete all {{ $entries->count() }} entries for this date?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-4 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm hover:bg-red-200">Delete All</button>
                                </form>
                            @elseif($firstEntry->status == 'pending')
                                <p class="text-sm text-yellow-600">This batch ({{ $entries->count() }} entries) is pending approval.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
