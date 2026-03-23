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
    <div class="max-w-2xl mx-auto">
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
            <form action="{{ route($timesheetRoutePrefix . 'timesheets.store') }}" method="POST">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="month" value="{{ $month }}">
                <div class="space-y-4">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                        <input type="date" id="date" name="date" required max="{{ now()->format('Y-m-d') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time <span class="text-red-500">*</span></label>
                            <input type="time" id="start_time" name="start_time" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time <span class="text-red-500">*</span></label>
                            <input type="time" id="end_time" name="end_time" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label for="break_duration" class="block text-sm font-medium text-gray-700 mb-2">Break Duration (hours)</label>
                        <input type="number" id="break_duration" name="break_duration" step="0.25" min="0" max="4" value="1"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="e.g., 1 for 1 hour break">
                        <p class="text-xs text-gray-500 mt-1">Break time will be excluded from total hours</p>
                    </div>

                    <!-- Project and Task Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Project <span class="text-red-500">*</span></label>
                            <select id="project_id" name="project_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" data-project-dept-id="{{ $project->project_department_id }}" data-tasks="{{ json_encode($project->tasks ?? []) }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
<div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Task <span class="text-red-500">*</span></label>
                            <select id="task" name="task" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 block min-h-[42px]" required>
                                <option value="">Select project first to load predefined tasks</option>
                            </select>
                            <input type="text" id="task_text" name="task_text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 hidden" placeholder="Enter custom task">
                            <input type="hidden" id="task_hidden" name="task" value="">
                            <button type="button" id="toggle-task-mode" class="mt-1 text-xs text-blue-600 hover:text-blue-800 font-medium">Use predefined tasks</button>
                        </div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-700">
                            <strong>Note:</strong> Minimum 6.5 hours required per day (excluding break). 
                            Weekly target is 40 hours.
                        </p>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3" 
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
                
                document.addEventListener('DOMContentLoaded', function() {
                    // Project/Task filtering JS
                    const projectSelect = document.getElementById('project_id');
                    const taskSelect = document.getElementById('task');
                    const taskText = document.getElementById('task_text');
                    const taskHidden = document.getElementById('task_hidden');
                    
                    projectSelect.addEventListener('change', function() {
                        const projectId = this.value;
                        const option = this.selectedOptions[0];
                        let projectTasks = JSON.parse(option.dataset.tasks || '[]');
                        
                        // Robust fallback if project tasks empty
                        if (!projectTasks || projectTasks.length === 0) {
                            console.log('Project tasks empty, using department fallback');
                            if (userDepartmentTasks && userDepartmentTasks.length > 0) {
                                projectTasks = userDepartmentTasks;
                            } else {
                                projectTasks = ['General Work', 'Meeting', 'Documentation', 'UI/UX', 'Coding', 'Testing', 'DevOps', 'Project Meeting'];
                            }
                        }
                        console.log('Loaded tasks for project:', projectTasks);
                        
                        taskSelect.innerHTML = '<option value="">Select predefined task</option>';
                        taskSelect.disabled = false;
                        taskSelect.classList.remove('hidden');
                        taskSelect.style.display = 'block !important';
                        taskSelect.style.visibility = 'visible';
                        taskSelect.style.height = 'auto';
                        taskSelect.style.minHeight = '42px';
                        taskText.classList.add('hidden');
                        taskText.style.display = 'none';
                        taskSelect.required = true;
                        taskText.required = false;
                        taskHidden.value = '';
                        
                        console.log('Task dropdown forced visible:', {
                            display: taskSelect.style.display,
                            visibility: taskSelect.style.visibility,
                            height: taskSelect.style.height,
                            classList: taskSelect.className
                        });
                        
                        projectTasks.forEach((task, index) => {
                            const opt = document.createElement('option');
                            opt.value = task;
                            opt.textContent = task;
                            taskSelect.appendChild(opt);
                        });
                        
                        console.log('Task select populated:', taskSelect.innerHTML);
                        console.log('Task select children:', taskSelect.children.length, 'options');
                    });
                    
                    taskSelect.addEventListener('change', function() {
                        taskHidden.value = this.value;
                    });
                    
                    // Initial state - task dropdown visible, text hidden
                    taskText.classList.add('hidden');
                    taskText.style.display = 'none';
                    taskSelect.classList.remove('hidden');
                    taskSelect.style.display = 'block';
                    taskSelect.style.visibility = 'visible';
                    taskSelect.style.height = 'auto';
                    taskSelect.style.minHeight = '42px';
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
                    @foreach($existing as $entry)
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $entry->date->format('M d, Y') }}</p>
                                    <p class="text-sm text-gray-500">
                                        @if($entry->start_time && $entry->end_time)
                                            {{ $entry->start_time }} - {{ $entry->end_time }} 
                                            @if($entry->break_duration > 0)
                                                (Break: {{ $entry->break_duration }} hrs)
                                            @endif
                                        @endif
                                        = {{ number_format($entry->hours, 2) }} hrs
                                    </p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs {{ match($entry->status) { 'draft' => 'bg-gray-100 text-gray-700', 'pending' => 'bg-yellow-100 text-yellow-700', 'rejected' => 'bg-red-100 text-red-700' } }}">
                                    {{ ucfirst($entry->status) }}
                                </span>
                            </div>
                            @if($entry->rejection_reason)
                                <div class="mb-3 p-2 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-700"><strong>Rejection Reason:</strong> {{ $entry->rejection_reason }}</p>
                                </div>
                            @endif
                            @if($entry->description)
                                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($entry->description, 100) }}</p>
                            @endif
                            @if($entry->status == 'draft' || $entry->status == 'rejected')
                                <form action="{{ route($timesheetRoutePrefix . 'timesheets.updateDraft', $entry->id) }}" method="POST" class="inline-flex flex-wrap items-center gap-2 flex-wrap">
                                    @csrf @method('PATCH')
                                    <input type="time" name="start_time" value="{{ $entry->start_time }}" class="px-2 py-1 border rounded text-sm" required>
                                    <span class="text-gray-500">to</span>
                                    <input type="time" name="end_time" value="{{ $entry->end_time }}" class="px-2 py-1 border rounded text-sm" required>
                                    <input type="number" name="break_duration" value="{{ $entry->break_duration ?? 1 }}" step="0.25" min="0" max="4" 
                                        class="w-16 px-2 py-1 border rounded text-sm" title="Break (hours)">
                                    <select name="project_id" class="px-2 py-1 border rounded text-sm" required>
                                        <option value="">Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ $entry->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="task" value="{{ $entry->task }}" class="px-2 py-1 border rounded text-sm w-24" required placeholder="Task">
                                    <button type="submit" class="px-4 py-1.5 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Update</button>
                                </form>
                                @if($entry->status == 'draft')
                                    <form action="{{ route($timesheetRoutePrefix . 'timesheets.submit', $entry->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-1.5 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">Submit for Approval</button>
                                    </form>
                                @endif
                                <form action="{{ route($timesheetRoutePrefix . 'timesheets.destroy', $entry->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this entry?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-4 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm hover:bg-red-200">Delete</button>
                                </form>
                            @elseif($entry->status == 'pending')
                                <p class="text-sm text-yellow-600">This entry is pending approval.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

