@extends('layouts.app')

@section('title', 'Edit Client')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('dashboard') }}" class="hover:text-green-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('clients.index') }}" class="hover:text-green-600">Clients</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Edit Client</li>
            </ol>
        </nav>

        <!-- Client Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('clients.update', $id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Client Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Client Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $client['name'] ?? '') }}" placeholder="Enter client name" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">The full name of the client</p>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $client['phone'] ?? '') }}" placeholder="+1-555-0123" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    <p class="text-xs text-gray-500 mt-1">Contact phone number</p>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $client['email'] ?? '') }}" placeholder="client@example.com" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">Primary contact email</p>
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea id="address" name="address" rows="3" placeholder="Full address including street, city, state, zip" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all resize-none">{{ old('address', $client['address'] ?? '') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Complete address for billing and contact purposes</p>
            </div>

            <!-- Contact Persons -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-sm font-medium text-gray-700">Contact Persons</label>
                    <button type="button" id="add-contact" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Contact
                    </button>
                </div>
                <div id="contact-persons" class="space-y-4">
                    <!-- Contact persons will be populated by JavaScript -->
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Status</label>
                <div class="flex items-center space-x-6">
                    <label class="flex items-center">
                        <input type="radio" name="status" value="active" {{ old('status', $client['status'] ?? '') == 'active' ? 'checked' : '' }} class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="status" value="inactive" {{ old('status', $client['status'] ?? '') == 'inactive' ? 'checked' : '' }} class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Inactive</span>
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-1">Set the client status</p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('clients.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-colors font-medium shadow-lg shadow-green-500/30">
                    Update Client
                </button>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let contactIndex = 0;

    // Populate existing contact persons
    const existingContacts = @json(old('contact_persons', $client['contact_persons'] ?? []));
    const container = document.getElementById('contact-persons');

    if (existingContacts.length > 0) {
        existingContacts.forEach((contact, index) => {
            const contactDiv = createContactPerson(index, contact);
            container.appendChild(contactDiv);
        });
        contactIndex = existingContacts.length;
    } else {
        // Add default empty contact if none exist
        const contactDiv = createContactPerson(0, {});
        container.appendChild(contactDiv);
        contactIndex = 1;
    }

    document.getElementById('add-contact').addEventListener('click', function() {
        const container = document.getElementById('contact-persons');
        const newContact = createContactPerson(contactIndex, {});
        container.appendChild(newContact);
        contactIndex++;

        // Show remove buttons if more than one contact
        updateRemoveButtons();
    });

    function createContactPerson(index, data = {}) {
        const div = document.createElement('div');
        div.className = 'contact-person bg-gray-50 p-4 rounded-lg border';
        div.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-medium text-gray-700">Contact Person ${index + 1}</h4>
                <button type="button" class="remove-contact text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="contact_persons[${index}][name]" value="${data.name || ''}" placeholder="Contact person name" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                    <input type="text" name="contact_persons[${index}][designation]" value="${data.designation || ''}" placeholder="Job title/designation" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="contact_persons[${index}][email]" value="${data.email || ''}" placeholder="contact@example.com" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="tel" name="contact_persons[${index}][phone]" value="${data.phone || ''}" placeholder="+1-555-0123" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                </div>
            </div>
        `;

        div.querySelector('.remove-contact').addEventListener('click', function() {
            div.remove();
            updateRemoveButtons();
            renumberContacts();
        });

        return div;
    }

    function updateRemoveButtons() {
        const contacts = document.querySelectorAll('.contact-person');
        const removeButtons = document.querySelectorAll('.remove-contact');

        if (contacts.length > 1) {
            removeButtons.forEach(btn => btn.style.display = 'block');
        } else {
            removeButtons.forEach(btn => btn.style.display = 'none');
        }
    }

    function renumberContacts() {
        const contacts = document.querySelectorAll('.contact-person');
        contacts.forEach((contact, index) => {
            const title = contact.querySelector('h4');
            title.textContent = `Contact Person ${index + 1}`;

            // Update input names
            const inputs = contact.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.name;
                const field = name.split('[')[1].split(']')[0];
                input.name = `contact_persons[${index}][${field}]`;
            });
        });
        contactIndex = contacts.length;
    }

    // Initial setup
    updateRemoveButtons();
});
</script>
@endsection