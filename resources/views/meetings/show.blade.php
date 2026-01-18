@extends('layouts.app')

@section('title', $meeting['title'] . ' - ' . $project['name'])

@section('content')

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
        <li class="text-gray-900 font-medium">{{ $meeting['title'] }}</li>
    </ol>
</nav>

<!-- Meeting Header -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $meeting['title'] }}</h1>
            @if(!empty($meeting['description']))
                <p class="text-gray-600 mb-4">{{ $meeting['description'] }}</p>
            @endif
            <div class="flex items-center space-x-4 text-gray-600 mb-4">
                <span class="flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ date('M j, Y', strtotime($meeting['meeting_date'])) }} at {{ date('g:i A', strtotime($meeting['meeting_time'])) }}</span>
                </span>
                <span class="flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $meeting['duration'] }} minutes</span>
                </span>
                <span class="flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ $meeting['location'] }}</span>
                </span>
            </div>
            <div class="flex items-center space-x-4">
                @php
                    $statusColors = [
                        'scheduled' => 'bg-blue-100 text-blue-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                    $statusLabels = [
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ];
                    $typeLabels = [
                        'kickoff' => 'Kickoff Meeting',
                        'review' => 'Review Meeting',
                        'status' => 'Status Update',
                        'planning' => 'Planning Meeting',
                        'retrospective' => 'Retrospective',
                        'other' => 'Other',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$meeting['status']] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusLabels[$meeting['status']] ?? ucwords(str_replace('_', ' ', $meeting['status'])) }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                    {{ $typeLabels[$meeting['meeting_type']] ?? ucwords(str_replace('_', ' ', $meeting['meeting_type'])) }}
                </span>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('projects.meetings.edit', [$project['id'], $meeting['id']]) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit MOM">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
            <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Meeting Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Attendees -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Attendees</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($meeting['attendees'] as $attendee)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                        {{ $attendee }}
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Agenda -->
        @if(!empty($meeting['agenda']))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Agenda</h3>
            <ol class="list-decimal list-inside space-y-2">
                @foreach($meeting['agenda'] as $item)
                    <li class="text-gray-700">{{ $item }}</li>
                @endforeach
            </ol>
        </div>
        @endif

        <!-- Meeting Minutes (only for completed meetings) -->
        @if($meeting['status'] === 'completed')
        <div class="space-y-6">

            <!-- Discussion Points -->
            @if(!empty($meeting['discussion_points']))
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Discussion Points</h3>
                <ul class="list-disc list-inside space-y-2">
                    @foreach($meeting['discussion_points'] as $point)
                        <li class="text-gray-700">{{ $point }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Decisions Made -->
            @if(!empty($meeting['decisions']))
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Decisions Made</h3>
                <ul class="list-disc list-inside space-y-2">
                    @foreach($meeting['decisions'] as $decision)
                        <li class="text-gray-700">{{ $decision }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Action Items -->
            @if(!empty($meeting['action_items']))
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Action Items</h3>
                <ul class="list-disc list-inside space-y-2">
                    @foreach($meeting['action_items'] as $item)
                        <li class="text-gray-700">{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Next Meeting -->
            @if(!empty($meeting['next_meeting_date']))
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Next Meeting</h3>
                <p class="text-gray-700">Scheduled for {{ date('M j, Y', strtotime($meeting['next_meeting_date'])) }}</p>
            </div>
            @endif

        </div>
        @endif

    </div>

    <!-- Sidebar -->
    <div class="space-y-6">

        <!-- Meeting Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Meeting Information</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Project</p>
                    <p class="font-medium text-gray-800">{{ $project['name'] }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Client</p>
                    <p class="font-medium text-gray-800">{{ $project['client_name'] }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Created By</p>
                    <p class="font-medium text-gray-800">{{ $meeting['created_by'] }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Meeting Type</p>
                    <p class="font-medium text-gray-800">{{ $typeLabels[$meeting['meeting_type']] ?? ucwords(str_replace('_', ' ', $meeting['meeting_type'])) }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('projects.meetings.edit', [$project['id'], $meeting['id']]) }}" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit MOM
                </a>

                @if($meeting['status'] === 'completed')
                <a href="{{ route('projects.meetings.download', [$project['id'], $meeting['id']]) }}" class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download MOM
                </a>

                <button type="button" data-meeting-id="{{ $meeting['id'] }}" data-meeting-title="{{ addslashes($meeting['title']) }}" data-attendees="{{ json_encode($meeting['attendees']) }}" onclick="openSendModal(this)" class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Send MOM to Attendees
                </button>
                @endif

                <a href="{{ route('projects.meetings.index', $project['id']) }}" class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Meetings
                </a>
            </div>
        </div>

    </div>

</div>

<!-- Send MOM Modal -->
<div id="sendModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Send MOM</h3>
            <button onclick="closeSendModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="sendMOMForm" action="" method="POST">
            @csrf
            <div class="space-y-4">
                <!-- Email Subject -->
                <div>
                    <label for="email_subject" class="block text-sm font-medium text-gray-700 mb-2">Email Subject</label>
                    <input type="text" id="email_subject" name="subject" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                </div>

                <!-- Recipients -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipients</label>
                    <div class="space-y-2">
                        <div id="recipients-list" class="flex flex-wrap gap-2 min-h-12 p-2 border border-gray-200 rounded-lg">
                            <!-- Recipients will be added here -->
                        </div>
                        <div class="flex space-x-2">
                            <input type="email" id="new-recipient" placeholder="Add recipient email" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <button type="button" id="add-recipient" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                Add
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="recipients" id="recipients-hidden">
                    <p class="text-xs text-gray-500 mt-1">Meeting attendees are pre-selected. Add additional recipients if needed.</p>
                </div>
            </div>

            <!-- Modal Actions -->
            <div class="flex items-center justify-end space-x-4 mt-6 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeSendModal()" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-colors font-medium shadow-lg shadow-blue-500/30">
                    Send MOM
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let currentMeetingId = null;
let recipients = [];

function openSendModal(button) {
    const meetingId = button.dataset.meetingId;
    const meetingTitle = button.dataset.meetingTitle;
    const attendees = JSON.parse(button.dataset.attendees);

    currentMeetingId = meetingId;
    recipients = attendees.map(attendee => ({ name: attendee, email: attendee + '@example.com' })); // Mock email generation

    // Update form action
    document.getElementById('sendMOMForm').action = `/projects/{{ $project['id'] }}/meetings/${meetingId}/send`;

    // Set default subject
    document.getElementById('email_subject').value = `MOM: ${meetingTitle}`;

    // Populate recipients
    updateRecipientsDisplay();

    // Show modal
    document.getElementById('sendModal').classList.remove('hidden');
}

function closeSendModal() {
    document.getElementById('sendModal').classList.add('hidden');
    currentMeetingId = null;
    recipients = [];
}

function updateRecipientsDisplay() {
    const container = document.getElementById('recipients-list');
    container.innerHTML = '';

    recipients.forEach((recipient, index) => {
        const tag = document.createElement('span');
        tag.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800';
        tag.innerHTML = `
            ${recipient.email}
            <button type="button" class="ml-2 text-blue-600 hover:text-blue-800" onclick="removeRecipient(${index})">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        container.appendChild(tag);
    });

    // Update hidden input
    document.getElementById('recipients-hidden').value = JSON.stringify(recipients.map(r => r.email));
}

function removeRecipient(index) {
    recipients.splice(index, 1);
    updateRecipientsDisplay();
}

// Add recipient functionality
document.getElementById('add-recipient').addEventListener('click', function() {
    const emailInput = document.getElementById('new-recipient');
    const email = emailInput.value.trim();

    if (email && isValidEmail(email)) {
        // Check if already exists
        if (!recipients.some(r => r.email === email)) {
            recipients.push({ name: email.split('@')[0], email: email });
            updateRecipientsDisplay();
            emailInput.value = '';
        } else {
            alert('This email is already added.');
        }
    } else {
        alert('Please enter a valid email address.');
    }
});

document.getElementById('new-recipient').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('add-recipient').click();
    }
});

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Close modal when clicking outside
document.getElementById('sendModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSendModal();
    }
});
</script>
@endsection