@extends('layouts.app')

@section('title', 'Edit Meeting MOM - ' . $project['name'])

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('dashboard') }}" class="hover:text-purple-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('projects.index') }}" class="hover:text-purple-600">Projects</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('projects.show', $project['id']) }}" class="hover:text-purple-600">{{ $project['name'] }}</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('projects.meetings.index', $project['id']) }}" class="hover:text-purple-600">Meetings</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Edit MOM</li>
            </ol>
        </nav>

        <!-- Meeting Form -->
        <form action="{{ route('projects.meetings.update', [$project['id'], $meeting['id']]) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-8">
        @csrf
        @method('PUT')

        <!-- Meeting Details -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Meeting Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Meeting Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Meeting Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $meeting['title']) }}" placeholder="e.g., Project Kickoff Meeting" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">Descriptive title for the meeting</p>
                </div>

                <!-- Meeting Date -->
                <div>
                    <label for="meeting_date" class="block text-sm font-medium text-gray-700 mb-2">Meeting Date</label>
                    <input type="date" id="meeting_date" name="meeting_date" value="{{ old('meeting_date', date('Y-m-d', strtotime($meeting['meeting_date']))) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">Date when the meeting occurred</p>
                </div>

                <!-- Meeting Time -->
                <div>
                    <label for="meeting_time" class="block text-sm font-medium text-gray-700 mb-2">Meeting Time</label>
                    <input type="time" id="meeting_time" name="meeting_time" value="{{ old('meeting_time', $meeting['meeting_time']) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">Time when the meeting started</p>
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                    <input type="number" id="duration" name="duration" value="{{ old('duration', $meeting['duration']) }}" min="15" max="480" step="15" placeholder="120" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">Meeting duration in minutes</p>
                </div>

                <!-- Meeting Type -->
                <div>
                    <label for="meeting_type" class="block text-sm font-medium text-gray-700 mb-2">Meeting Type</label>
                    <select id="meeting_type" name="meeting_type" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white" required>
                        <option value="">Select meeting type</option>
                        <option value="kickoff" {{ old('meeting_type', $meeting['meeting_type']) == 'kickoff' ? 'selected' : '' }}>Kickoff Meeting</option>
                        <option value="review" {{ old('meeting_type', $meeting['meeting_type']) == 'review' ? 'selected' : '' }}>Review Meeting</option>
                        <option value="status" {{ old('meeting_type', $meeting['meeting_type']) == 'status' ? 'selected' : '' }}>Status Update</option>
                        <option value="planning" {{ old('meeting_type', $meeting['meeting_type']) == 'planning' ? 'selected' : '' }}>Planning Meeting</option>
                        <option value="retrospective" {{ old('meeting_type', $meeting['meeting_type']) == 'retrospective' ? 'selected' : '' }}>Retrospective</option>
                        <option value="other" {{ old('meeting_type', $meeting['meeting_type']) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Type of meeting</p>
                </div>

                <!-- Location -->
                <div class="md:col-span-2">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" id="location" name="location" value="{{ old('location', $meeting['location']) }}" placeholder="e.g., Conference Room A, Virtual Meeting" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">Physical or virtual location of the meeting</p>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                    <div class="relative">
                        <textarea id="description" name="description" rows="4" placeholder="Brief description of the meeting purpose and objectives..." class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-vertical">{{ old('description', $meeting['description'] ?? '') }}</textarea>
                        <div class="absolute top-2 right-2 flex space-x-1">
                            <button type="button" onclick="formatText('bold')" class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded" title="Bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"></path>
                                </svg>
                            </button>
                            <button type="button" onclick="formatText('italic')" class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded" title="Italic">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                            </button>
                            <button type="button" onclick="formatText('underline')" class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded" title="Underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h8a1 1 0 011 1v3m0 0v3a1 1 0 01-1 1H6a1 1 0 01-1-1V4z"></path>
                                </svg>
                            </button>
                            <button type="button" onclick="formatText('insertUnorderedList')" class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded" title="Bullet List">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </button>
                            <button type="button" onclick="formatText('insertOrderedList')" class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded" title="Numbered List">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Optional description of the meeting</p>
                </div>
            </div>
        </div>

        <!-- Attendees -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Attendees</h3>
            <div class="space-y-2">
                <div class="flex flex-wrap gap-2" id="attendees-container">
                    <!-- Attendees will be populated by JavaScript -->
                </div>
                <div class="flex space-x-2">
                    <select id="attendee-select" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <option value="">Select team member</option>
                        @foreach($teamMembers as $member)
                            <option value="{{ $member['name'] }}">{{ $member['name'] }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="add-attendee" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                        Add
                    </button>
                </div>
            </div>
            <input type="hidden" name="attendees" id="attendees-hidden" value="{{ old('attendees', json_encode($meeting['attendees'] ?? [])) }}">
            <p class="text-xs text-gray-500 mt-2">People who attended the meeting</p>
        </div>

        <!-- Agenda -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Agenda</h3>
            <div class="space-y-2">
                <div class="flex flex-wrap gap-2" id="agenda-container">
                    <!-- Agenda items will be populated by JavaScript -->
                </div>
                <div class="flex space-x-2">
                    <input type="text" id="agenda-input" placeholder="Add agenda item" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    <button type="button" id="add-agenda" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                        Add
                    </button>
                </div>
            </div>
            <input type="hidden" name="agenda" id="agenda-hidden" value="{{ old('agenda', json_encode($meeting['agenda'] ?? [])) }}">
            <p class="text-xs text-gray-500 mt-2">Topics discussed in the meeting</p>
        </div>

        <!-- Meeting Content (for completed meetings) -->
        <div id="meeting-content" style="{{ old('status', $meeting['status']) == 'completed' ? '' : 'display: none;' }}">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Meeting Minutes</h3>

            <!-- Discussion Points -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Discussion Points</label>
                <div class="space-y-2">
                    <div class="flex flex-wrap gap-2" id="discussion-container">
                        <!-- Discussion points will be populated by JavaScript -->
                    </div>
                    <div class="flex space-x-2">
                        <input type="text" id="discussion-input" placeholder="Add discussion point" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <button type="button" id="add-discussion" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                            Add
                        </button>
                    </div>
                </div>
                <input type="hidden" name="discussion_points" id="discussion-hidden" value="{{ old('discussion_points', json_encode($meeting['discussion_points'] ?? [])) }}">
                <p class="text-xs text-gray-500 mt-2">Key points discussed during the meeting</p>
            </div>

            <!-- Decisions -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Decisions Made</label>
                <div class="space-y-2">
                    <div class="flex flex-wrap gap-2" id="decisions-container">
                        <!-- Decisions will be populated by JavaScript -->
                    </div>
                    <div class="flex space-x-2">
                        <input type="text" id="decision-input" placeholder="Add decision" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <button type="button" id="add-decision" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                            Add
                        </button>
                    </div>
                </div>
                <input type="hidden" name="decisions" id="decisions-hidden" value="{{ old('decisions', json_encode($meeting['decisions'] ?? [])) }}">
                <p class="text-xs text-gray-500 mt-2">Decisions made during the meeting</p>
            </div>

            <!-- Action Items -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Action Items</label>
                <div class="space-y-2">
                    <div class="flex flex-wrap gap-2" id="actions-container">
                        <!-- Action items will be populated by JavaScript -->
                    </div>
                    <div class="flex space-x-2">
                        <input type="text" id="action-input" placeholder="Add action item" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <button type="button" id="add-action" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                            Add
                        </button>
                    </div>
                </div>
                <input type="hidden" name="action_items" id="actions-hidden" value="{{ old('action_items', json_encode($meeting['action_items'] ?? [])) }}">
                <p class="text-xs text-gray-500 mt-2">Action items assigned during the meeting</p>
            </div>

            <!-- Next Meeting Date -->
            <div>
                <label for="next_meeting_date" class="block text-sm font-medium text-gray-700 mb-2">Next Meeting Date (Optional)</label>
                <input type="date" id="next_meeting_date" name="next_meeting_date" value="{{ old('next_meeting_date', $meeting['next_meeting_date'] ?? '') }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-500 mt-1">Date for the next scheduled meeting</p>
            </div>
        </div>

        <!-- Status -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Meeting Status</label>
                <div class="flex items-center space-x-6">
                    @php
                        $statuses = ['scheduled', 'completed', 'cancelled'];
                        $statusLabels = ['scheduled' => 'Scheduled', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
                    @endphp
                    @foreach($statuses as $status)
                        <label class="flex items-center">
                            <input type="radio" name="status" value="{{ $status }}" {{ old('status', $meeting['status']) == $status ? 'checked' : '' }} class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500" id="status-{{ $status }}">
                            <span class="ml-2 text-sm text-gray-700">{{ $statusLabels[$status] }}</span>
                        </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-1">Current status of the meeting</p>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
            <a href="{{ route('projects.meetings.index', $project['id']) }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-colors font-medium shadow-lg shadow-purple-500/30">
                Update MOM
            </button>
        </div>
    </form>
    </div>
</div>

<script>
// Rich text formatting function
function formatText(command) {
    const textarea = document.getElementById('description');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);

    let replacement = '';
    switch(command) {
        case 'bold':
            replacement = `**${selectedText}**`;
            break;
        case 'italic':
            replacement = `*${selectedText}*`;
            break;
        case 'underline':
            replacement = `<u>${selectedText}</u>`;
            break;
        case 'insertUnorderedList':
            replacement = `• ${selectedText}`;
            break;
        case 'insertOrderedList':
            replacement = `1. ${selectedText}`;
            break;
    }

    if (replacement) {
        textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + replacement.length, start + replacement.length);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Status change handler
    document.querySelectorAll('input[name="status"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const meetingContent = document.getElementById('meeting-content');
            if (this.value === 'completed') {
                meetingContent.style.display = 'block';
            } else {
                meetingContent.style.display = 'none';
            }
        });
    });

    // Attendees management
    let attendees = @json(old('attendees', $meeting['attendees'] ?? []));
    const attendeesContainer = document.getElementById('attendees-container');
    const attendeeSelect = document.getElementById('attendee-select');
    const addAttendeeBtn = document.getElementById('add-attendee');
    const attendeesHidden = document.getElementById('attendees-hidden');

    function updateAttendeesDisplay() {
        attendeesContainer.innerHTML = '';
        attendees.forEach((attendee, index) => {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800';
            tag.innerHTML = `
                ${attendee}
                <button type="button" class="ml-2 text-purple-600 hover:text-purple-800" onclick="removeAttendee(${index})">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            attendeesContainer.appendChild(tag);
        });
        attendeesHidden.value = JSON.stringify(attendees);
    }

    updateAttendeesDisplay();

    addAttendeeBtn.addEventListener('click', function() {
        const value = attendeeSelect.value;
        if (value && !attendees.includes(value)) {
            attendees.push(value);
            updateAttendeesDisplay();
            attendeeSelect.value = '';
        }
    });

    window.removeAttendee = function(index) {
        attendees.splice(index, 1);
        updateAttendeesDisplay();
    };

    // Agenda management
    let agenda = @json(old('agenda', $meeting['agenda'] ?? []));
    const agendaContainer = document.getElementById('agenda-container');
    const agendaInput = document.getElementById('agenda-input');
    const addAgendaBtn = document.getElementById('add-agenda');
    const agendaHidden = document.getElementById('agenda-hidden');

    function updateAgendaDisplay() {
        agendaContainer.innerHTML = '';
        agenda.forEach((item, index) => {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800';
            tag.innerHTML = `
                ${item}
                <button type="button" class="ml-2 text-blue-600 hover:text-blue-800" onclick="removeAgenda(${index})">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            agendaContainer.appendChild(tag);
        });
        agendaHidden.value = JSON.stringify(agenda);
    }

    updateAgendaDisplay();

    addAgendaBtn.addEventListener('click', function() {
        const value = agendaInput.value.trim();
        if (value && !agenda.includes(value)) {
            agenda.push(value);
            updateAgendaDisplay();
            agendaInput.value = '';
        }
    });

    agendaInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addAgendaBtn.click();
        }
    });

    window.removeAgenda = function(index) {
        agenda.splice(index, 1);
        updateAgendaDisplay();
    };

    // Discussion points management
    let discussionPoints = @json(old('discussion_points', $meeting['discussion_points'] ?? []));
    const discussionContainer = document.getElementById('discussion-container');
    const discussionInput = document.getElementById('discussion-input');
    const addDiscussionBtn = document.getElementById('add-discussion');
    const discussionHidden = document.getElementById('discussion-hidden');

    function updateDiscussionDisplay() {
        discussionContainer.innerHTML = '';
        discussionPoints.forEach((point, index) => {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800';
            tag.innerHTML = `
                ${point}
                <button type="button" class="ml-2 text-green-600 hover:text-green-800" onclick="removeDiscussion(${index})">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            discussionContainer.appendChild(tag);
        });
        discussionHidden.value = JSON.stringify(discussionPoints);
    }

    updateDiscussionDisplay();

    addDiscussionBtn.addEventListener('click', function() {
        const value = discussionInput.value.trim();
        if (value && !discussionPoints.includes(value)) {
            discussionPoints.push(value);
            updateDiscussionDisplay();
            discussionInput.value = '';
        }
    });

    discussionInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addDiscussionBtn.click();
        }
    });

    window.removeDiscussion = function(index) {
        discussionPoints.splice(index, 1);
        updateDiscussionDisplay();
    };

    // Decisions management
    let decisions = @json(old('decisions', $meeting['decisions'] ?? []));
    const decisionsContainer = document.getElementById('decisions-container');
    const decisionInput = document.getElementById('decision-input');
    const addDecisionBtn = document.getElementById('add-decision');
    const decisionsHidden = document.getElementById('decisions-hidden');

    function updateDecisionsDisplay() {
        decisionsContainer.innerHTML = '';
        decisions.forEach((decision, index) => {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800';
            tag.innerHTML = `
                ${decision}
                <button type="button" class="ml-2 text-yellow-600 hover:text-yellow-800" onclick="removeDecision(${index})">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            decisionsContainer.appendChild(tag);
        });
        decisionsHidden.value = JSON.stringify(decisions);
    }

    updateDecisionsDisplay();

    addDecisionBtn.addEventListener('click', function() {
        const value = decisionInput.value.trim();
        if (value && !decisions.includes(value)) {
            decisions.push(value);
            updateDecisionsDisplay();
            decisionInput.value = '';
        }
    });

    decisionInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addDecisionBtn.click();
        }
    });

    window.removeDecision = function(index) {
        decisions.splice(index, 1);
        updateDecisionsDisplay();
    };

    // Action items management
    let actionItems = @json(old('action_items', $meeting['action_items'] ?? []));
    const actionsContainer = document.getElementById('actions-container');
    const actionInput = document.getElementById('action-input');
    const addActionBtn = document.getElementById('add-action');
    const actionsHidden = document.getElementById('actions-hidden');

    function updateActionsDisplay() {
        actionsContainer.innerHTML = '';
        actionItems.forEach((item, index) => {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-red-100 text-red-800';
            tag.innerHTML = `
                ${item}
                <button type="button" class="ml-2 text-red-600 hover:text-red-800" onclick="removeAction(${index})">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            actionsContainer.appendChild(tag);
        });
        actionsHidden.value = JSON.stringify(actionItems);
    }

    updateActionsDisplay();

    addActionBtn.addEventListener('click', function() {
        const value = actionInput.value.trim();
        if (value && !actionItems.includes(value)) {
            actionItems.push(value);
            updateActionsDisplay();
            actionInput.value = '';
        }
    });

    actionInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addActionBtn.click();
        }
    });

    window.removeAction = function(index) {
        actionItems.splice(index, 1);
        updateActionsDisplay();
    };
});
</script>
@endsection