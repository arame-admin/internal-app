@extends('layouts.app')

@section('title', 'Create Meeting MOM - ' . $project['name'])

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.projects.index') }}" class="hover:text-purple-600">Projects</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.projects.show', $project['id']) }}" class="hover:text-purple-600">{{ $project['name'] }}</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.projects.meetings.index', $project['id']) }}" class="hover:text-purple-600">Meetings</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Create MOM</li>
            </ol>
        </nav>

        <!-- Meeting Form -->
        <form action="{{ route('admin.projects.meetings.store', $project['id']) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-8">
        @csrf

        <!-- Meeting Details -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Meeting Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Meeting Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Meeting Title</label>
                    <input type="text" id="title" name="title" placeholder="e.g., Project Kickoff Meeting" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">Descriptive title for the meeting</p>
                </div>

                <!-- Meeting Date -->
                <div>
                    <label for="meeting_date" class="block text-sm font-medium text-gray-700 mb-2">Meeting Date</label>
                    <input type="date" id="meeting_date" name="meeting_date" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">Date when the meeting occurred</p>
                </div>

                <!-- Meeting Time -->
                <div>
                    <label for="meeting_time" class="block text-sm font-medium text-gray-700 mb-2">Meeting Time</label>
                    <input type="time" id="meeting_time" name="meeting_time" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">Time when the meeting started</p>
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                    <input type="number" id="duration" name="duration" min="15" max="480" step="15" placeholder="120" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">Meeting duration in minutes</p>
                </div>

                <!-- Meeting Type -->
                <div>
                    <label for="meeting_type" class="block text-sm font-medium text-gray-700 mb-2">Meeting Type</label>
                    <select id="meeting_type" name="meeting_type" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white" required>
                        <option value="">Select meeting type</option>
                        <option value="kickoff">Kickoff Meeting</option>
                        <option value="review">Review Meeting</option>
                        <option value="status">Status Update</option>
                        <option value="planning">Planning Meeting</option>
                        <option value="retrospective">Retrospective</option>
                        <option value="other">Other</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Type of meeting</p>
                </div>

                <!-- Location -->
                <div class="md:col-span-2">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" id="location" name="location" placeholder="e.g., Conference Room A, Virtual Meeting" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">Physical or virtual location of the meeting</p>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Meeting Description</label>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-3 py-2 border-b border-gray-200 flex items-center space-x-2">
                        <button type="button" onclick="formatText('bold')" class="p-1 hover:bg-gray-200 rounded" title="Bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"></path>
                            </svg>
                        </button>
                        <button type="button" onclick="formatText('italic')" class="p-1 hover:bg-gray-200 rounded" title="Italic">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </button>
                        <button type="button" onclick="formatText('underline')" class="p-1 hover:bg-gray-200 rounded" title="Underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1m0 3l-8 8m8-8l8 8m-8-8v16"></path>
                            </svg>
                        </button>
                        <div class="h-4 w-px bg-gray-300"></div>
                        <button type="button" onclick="formatText('insertUnorderedList')" class="p-1 hover:bg-gray-200 rounded" title="Bullet List">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </button>
                        <button type="button" onclick="formatText('insertOrderedList')" class="p-1 hover:bg-gray-200 rounded" title="Numbered List">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1m0 3l-8 8m8-8l8 8m-8-8v16"></path>
                            </svg>
                        </button>
                    </div>
                    <textarea id="description" name="description" rows="6" placeholder="Describe the meeting objectives, key points to discuss, and expected outcomes..." class="w-full px-4 py-3 border-0 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none"></textarea>
                </div>
                <p class="text-xs text-gray-500 mt-1">Use the toolbar above for rich text formatting</p>
            </div>
        </div>

        <!-- Attendees -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Attendees</h3>
            <div class="space-y-2">
                <div class="flex flex-wrap gap-2" id="attendees-container">
                    <!-- Attendees will be added here -->
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
            <input type="hidden" name="attendees" id="attendees-hidden">
            <p class="text-xs text-gray-500 mt-2">People who attended the meeting</p>
        </div>

        <!-- Agenda -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Agenda</h3>
            <div class="space-y-2">
                <div class="flex flex-wrap gap-2" id="agenda-container">
                    <!-- Agenda items will be added here -->
                </div>
                <div class="flex space-x-2">
                    <input type="text" id="agenda-input" placeholder="Add agenda item" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    <button type="button" id="add-agenda" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                        Add
                    </button>
                </div>
            </div>
            <input type="hidden" name="agenda" id="agenda-hidden">
            <p class="text-xs text-gray-500 mt-2">Topics discussed in the meeting</p>
        </div>

        <!-- Meeting Content (for completed meetings) -->
        <div id="meeting-content" style="display: none;">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Meeting Minutes</h3>

            <!-- Discussion Points -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Discussion Points</label>
                <div class="space-y-2">
                    <div class="flex flex-wrap gap-2" id="discussion-container">
                        <!-- Discussion points will be added here -->
                    </div>
                    <div class="flex space-x-2">
                        <input type="text" id="discussion-input" placeholder="Add discussion point" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <button type="button" id="add-discussion" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                            Add
                        </button>
                    </div>
                </div>
                <input type="hidden" name="discussion_points" id="discussion-hidden">
                <p class="text-xs text-gray-500 mt-2">Key points discussed during the meeting</p>
            </div>

            <!-- Decisions -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Decisions Made</label>
                <div class="space-y-2">
                    <div class="flex flex-wrap gap-2" id="decisions-container">
                        <!-- Decisions will be added here -->
                    </div>
                    <div class="flex space-x-2">
                        <input type="text" id="decision-input" placeholder="Add decision" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <button type="button" id="add-decision" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                            Add
                        </button>
                    </div>
                </div>
                <input type="hidden" name="decisions" id="decisions-hidden">
                <p class="text-xs text-gray-500 mt-2">Decisions made during the meeting</p>
            </div>

            <!-- Action Items -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Action Items</label>
                <div class="space-y-2">
                    <div class="flex flex-wrap gap-2" id="actions-container">
                        <!-- Action items will be added here -->
                    </div>
                    <div class="flex space-x-2">
                        <input type="text" id="action-input" placeholder="Add action item" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <button type="button" id="add-action" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                            Add
                        </button>
                    </div>
                </div>
                <input type="hidden" name="action_items" id="actions-hidden">
                <p class="text-xs text-gray-500 mt-2">Action items assigned during the meeting</p>
            </div>

            <!-- Next Meeting Date -->
            <div>
                <label for="next_meeting_date" class="block text-sm font-medium text-gray-700 mb-2">Next Meeting Date (Optional)</label>
                <input type="date" id="next_meeting_date" name="next_meeting_date" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-500 mt-1">Date for the next scheduled meeting</p>
            </div>
        </div>

        <!-- Status -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Meeting Status</label>
                <div class="flex items-center space-x-6">
                    <label class="flex items-center">
                        <input type="radio" name="status" value="scheduled" class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500" checked>
                        <span class="ml-2 text-sm text-gray-700">Scheduled</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="status" value="completed" class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500" id="status-completed">
                        <span class="ml-2 text-sm text-gray-700">Completed</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="status" value="cancelled" class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-700">Cancelled</span>
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-1">Current status of the meeting</p>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.projects.meetings.index', $project['id']) }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-colors font-medium shadow-lg shadow-purple-500/30">
                Create MOM
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
    const attendees = [];
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
    const agenda = [];
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
    const discussionPoints = [];
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
    const decisions = [];
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
    const actionItems = [];
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