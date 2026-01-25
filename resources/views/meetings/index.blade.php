@extends('layouts.app')

@section('title', 'Meetings - ' . $project['name'])

@section('content')

<!-- Breadcrumb -->
<nav class="mb-6">
    <ol class="flex items-center space-x-2 text-sm text-gray-600">
        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600">Dashboard</a></li>
        <li><span class="text-gray-400">/</span></li>
        <li><a href="{{ route('admin.projects.index') }}" class="hover:text-purple-600">Projects</a></li>
        <li><span class="text-gray-400">/</span></li>
        <li><a href="{{ route('admin.projects.show', $project['id']) }}" class="hover:text-purple-600">{{ $project['name'] }}</a></li>
        <li><span class="text-gray-400">/</span></li>
        <li class="text-gray-900 font-medium">Meetings</li>
    </ol>
</nav>

<!-- Project Header -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $project['name'] }}</h1>
            <p class="text-gray-600 mt-1">Client: {{ $project['client_name'] }}</p>
        </div>
        <a href="{{ route('admin.projects.meetings.create', $project['id']) }}" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-colors font-medium shadow-lg shadow-purple-500/30">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Meeting
        </a>
    </div>
</div>

<!-- Meetings List -->
<div class="space-y-4">
    @forelse($meetings as $meeting)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $meeting['title'] }}</h3>
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
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$meeting['status']] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$meeting['status']] ?? ucwords(str_replace('_', ' ', $meeting['status'])) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Date & Time</p>
                        <p class="font-medium">{{ date('M j, Y', strtotime($meeting['meeting_date'])) }} at {{ date('g:i A', strtotime($meeting['meeting_time'])) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Duration</p>
                        <p class="font-medium">{{ $meeting['duration'] }} minutes</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Location</p>
                        <p class="font-medium">{{ $meeting['location'] }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">Attendees</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($meeting['attendees'] as $attendee)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-gray-100 text-gray-800">
                                {{ $attendee }}
                            </span>
                        @endforeach
                    </div>
                </div>

                @if(!empty($meeting['agenda']))
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">Agenda</p>
                    <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                        @foreach($meeting['agenda'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($meeting['status'] === 'completed' && (!empty($meeting['decisions']) || !empty($meeting['action_items'])))
                <div class="border-t border-gray-100 pt-4 mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if(!empty($meeting['decisions']))
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Decisions Made</p>
                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                @foreach($meeting['decisions'] as $decision)
                                    <li>{{ $decision }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(!empty($meeting['action_items']))
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Action Items</p>
                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                @foreach($meeting['action_items'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="flex items-center space-x-2 ml-6">
                <a href="{{ route('admin.projects.meetings.show', [$project['id'], $meeting['id']]) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </a>

                @if($meeting['status'] === 'completed')
                <a href="{{ route('admin.projects.meetings.download', [$project['id'], $meeting['id']]) }}" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Download MOM">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </a>

                <button type="button" data-meeting-id="{{ $meeting['id'] }}" data-meeting-title="{{ addslashes($meeting['title']) }}" data-attendees="{{ json_encode($meeting['attendees']) }}" onclick="openSendModal(this)" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Send MOM to Attendees">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </button>
                @endif

                <a href="{{ route('admin.projects.meetings.edit', [$project['id'], $meeting['id']]) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit MOM">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </a>
                <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No meetings yet</h3>
        <p class="text-gray-500 mb-6">Get started by scheduling your first meeting for this project.</p>
        <a href="{{ route('admin.projects.meetings.create', $project['id']) }}" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-colors font-medium shadow-lg shadow-purple-500/30">
            Schedule First Meeting
        </a>
    </div>
    @endforelse
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