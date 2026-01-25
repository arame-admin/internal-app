@extends('layouts.app')

@section('title', 'Add New Project')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('admin.projects.index') }}" class="hover:text-purple-600">Projects</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Add Project</li>
            </ol>
        </nav>

        <!-- Project Form -->
        <form action="{{ route('admin.projects.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-8">
        @csrf

        <!-- Basic Information -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Project Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter project name" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">The full name of the project</p>
                </div>

                <!-- Client -->
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                    <select id="client_id" name="client_id" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white" required>
                        <option value="">Select a client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client['id'] }}">{{ $client['name'] }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">The client this project belongs to</p>
                </div>

                <!-- Project Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Project Type</label>
                    <div class="space-y-3">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="project_type[]" value="web_application" class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm text-gray-700">Web Application</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="project_type[]" value="mobile_application" class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm text-gray-700">Mobile Application</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="project_type[]" value="desktop_application" class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm text-gray-700">Desktop Application</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="project_type[]" value="api_integration" class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm text-gray-700">API Integration</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="project_type[]" value="other" class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm text-gray-700">Other</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Select all applicable project types</p>
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select id="priority" name="priority" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white" required>
                        <option value="">Select priority</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Project priority level</p>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Describe the project scope, objectives, and requirements" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none"></textarea>
                <p class="text-xs text-gray-500 mt-1">Detailed project description</p>
            </div>
        </div>

        <!-- Timeline & Budget -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Timeline & Budget</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    <p class="text-xs text-gray-500 mt-1">Project start date</p>
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" id="end_date" name="end_date" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    <p class="text-xs text-gray-500 mt-1">Expected completion date</p>
                </div>

                <!-- Budget -->
                <div>
                    <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Budget</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                        <input type="number" id="budget" name="budget" placeholder="0.00" step="0.01" min="0" class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Project budget in USD</p>
                </div>
            </div>
        </div>

        <!-- Technologies & Features -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Technologies & Features</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Technologies -->
                <div>
                    <label for="technologies" class="block text-sm font-medium text-gray-700 mb-2">Technologies</label>
                    <div class="space-y-2">
                        <div class="flex flex-wrap gap-2" id="technologies-container">
                            <!-- Technologies will be added here -->
                        </div>
                        <div class="flex space-x-2">
                            <input type="text" id="technology-input" placeholder="Add technology (e.g., PHP, React)" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            <button type="button" id="add-technology" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                                Add
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="technologies" id="technologies-hidden">
                    <p class="text-xs text-gray-500 mt-1">Technologies used in the project</p>
                </div>

                <!-- Features -->
                <div>
                    <label for="features" class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                    <div class="space-y-2">
                        <div class="flex flex-wrap gap-2" id="features-container">
                            <!-- Features will be added here -->
                        </div>
                        <div class="flex space-x-2">
                            <input type="text" id="feature-input" placeholder="Add feature (e.g., User Authentication)" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            <button type="button" id="add-feature" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                                Add
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="features" id="features-hidden">
                    <p class="text-xs text-gray-500 mt-1">Key features of the project</p>
                </div>
            </div>
        </div>

        <!-- Project Requirements -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Project Requirements</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <label class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <input type="checkbox" name="design_required" value="1" class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <div>
                        <div class="font-medium text-gray-800">Design</div>
                        <div class="text-sm text-gray-500">UI/UX design required</div>
                    </div>
                </label>

                <label class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <input type="checkbox" name="mobile_app_required" value="1" class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <div>
                        <div class="font-medium text-gray-800">Mobile App</div>
                        <div class="text-sm text-gray-500">Mobile application development</div>
                    </div>
                </label>

                <label class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <input type="checkbox" name="web_app_required" value="1" class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <div>
                        <div class="font-medium text-gray-800">Web App</div>
                        <div class="text-sm text-gray-500">Web application development</div>
                    </div>
                </label>

                <label class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <input type="checkbox" name="deployment_required" value="1" class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <div>
                        <div class="font-medium text-gray-800">Deployment</div>
                        <div class="text-sm text-gray-500">Server deployment and setup</div>
                    </div>
                </label>

                <label class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <input type="checkbox" name="testing_required" value="1" class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <div>
                        <div class="font-medium text-gray-800">Testing</div>
                        <div class="text-sm text-gray-500">Quality assurance and testing</div>
                    </div>
                </label>

                <label class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <input type="checkbox" name="maintenance_required" value="1" class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <div>
                        <div class="font-medium text-gray-800">Maintenance</div>
                        <div class="text-sm text-gray-500">Post-launch support and maintenance</div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Team Members -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Team Members</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-sm font-medium text-gray-700">Assign Team Members</label>
                    <button type="button" id="add-team-member" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Member
                    </button>
                </div>
                <div id="team-members" class="space-y-4">
                    <!-- Team Member Template -->
                    <div class="team-member bg-gray-50 p-4 rounded-lg border">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-700">Team Member 1</h4>
                            <button type="button" class="remove-member text-red-600 hover:text-red-800" style="display: none;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Team Member</label>
                                <select name="team_members[0][user_id]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white" required>
                                    <option value="">Select team member</option>
                                    <option value="1">John Doe (Developer)</option>
                                    <option value="2">Jane Smith (Manager)</option>
                                    <option value="3">Bob Johnson (Designer)</option>
                                    <option value="4">Alice Brown (Tester)</option>
                                    <option value="5">Charlie Wilson (Developer)</option>
                                    <option value="6">Diana Davis (Manager)</option>
                                    <option value="7">Edward Miller (Developer)</option>
                                    <option value="8">Fiona Garcia (Designer)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role in Project</label>
                                <select name="team_members[0][role]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white" required>
                                    <option value="">Select role</option>
                                    <option value="project_manager">Project Manager</option>
                                    <option value="lead_developer">Lead Developer</option>
                                    <option value="developer">Developer</option>
                                    <option value="designer">Designer</option>
                                    <option value="tester">Tester</option>
                                    <option value="business_analyst">Business Analyst</option>
                                    <option value="devops">DevOps Engineer</option>
                                    <option value="qa_lead">QA Lead</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Initial Status</label>
                <div class="flex items-center space-x-6">
                    <label class="flex items-center">
                        <input type="radio" name="status" value="planning" class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500" checked>
                        <span class="ml-2 text-sm text-gray-700">Planning</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="status" value="in_progress" class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-700">In Progress</span>
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-1">Set the initial project status</p>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.projects.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-colors font-medium shadow-lg shadow-purple-500/30">
                Create Project
            </button>
        </div>
    </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Technologies management
    const technologies = [];
    const techContainer = document.getElementById('technologies-container');
    const techInput = document.getElementById('technology-input');
    const addTechBtn = document.getElementById('add-technology');
    const techHidden = document.getElementById('technologies-hidden');

    addTechBtn.addEventListener('click', function() {
        const value = techInput.value.trim();
        if (value && !technologies.includes(value)) {
            technologies.push(value);
            updateTechDisplay();
            techInput.value = '';
        }
    });

    techInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addTechBtn.click();
        }
    });

    function updateTechDisplay() {
        techContainer.innerHTML = '';
        technologies.forEach((tech, index) => {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800';
            tag.innerHTML = `
                ${tech}
                <button type="button" class="ml-2 text-purple-600 hover:text-purple-800" onclick="removeTechnology(${index})">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            techContainer.appendChild(tag);
        });
        techHidden.value = JSON.stringify(technologies);
    }

    window.removeTechnology = function(index) {
        technologies.splice(index, 1);
        updateTechDisplay();
    };

    // Features management
    const features = [];
    const featuresContainer = document.getElementById('features-container');
    const featureInput = document.getElementById('feature-input');
    const addFeatureBtn = document.getElementById('add-feature');
    const featuresHidden = document.getElementById('features-hidden');

    addFeatureBtn.addEventListener('click', function() {
        const value = featureInput.value.trim();
        if (value && !features.includes(value)) {
            features.push(value);
            updateFeaturesDisplay();
            featureInput.value = '';
        }
    });

    featureInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addFeatureBtn.click();
        }
    });

    function updateFeaturesDisplay() {
        featuresContainer.innerHTML = '';
        features.forEach((feature, index) => {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800';
            tag.innerHTML = `
                ${feature}
                <button type="button" class="ml-2 text-purple-600 hover:text-purple-800" onclick="removeFeature(${index})">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            featuresContainer.appendChild(tag);
        });
        featuresHidden.value = JSON.stringify(features);
    }

    window.removeFeature = function(index) {
        features.splice(index, 1);
        updateFeaturesDisplay();
    };

    // Team Members management
    let memberIndex = 1;

    document.getElementById('add-team-member').addEventListener('click', function() {
        const container = document.getElementById('team-members');
        const newMember = createTeamMember(memberIndex);
        container.appendChild(newMember);
        memberIndex++;

        // Show remove buttons if more than one member
        updateRemoveButtons();
    });

    function createTeamMember(index) {
        const div = document.createElement('div');
        div.className = 'team-member bg-gray-50 p-4 rounded-lg border';
        div.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-medium text-gray-700">Team Member ${index + 1}</h4>
                <button type="button" class="remove-member text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Team Member</label>
                    <select name="team_members[${index}][user_id]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white" required>
                        <option value="">Select team member</option>
                        <option value="1">John Doe (Developer)</option>
                        <option value="2">Jane Smith (Manager)</option>
                        <option value="3">Bob Johnson (Designer)</option>
                        <option value="4">Alice Brown (Tester)</option>
                        <option value="5">Charlie Wilson (Developer)</option>
                        <option value="6">Diana Davis (Manager)</option>
                        <option value="7">Edward Miller (Developer)</option>
                        <option value="8">Fiona Garcia (Designer)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role in Project</label>
                    <select name="team_members[${index}][role]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white" required>
                        <option value="">Select role</option>
                        <option value="project_manager">Project Manager</option>
                        <option value="lead_developer">Lead Developer</option>
                        <option value="developer">Developer</option>
                        <option value="designer">Designer</option>
                        <option value="tester">Tester</option>
                        <option value="business_analyst">Business Analyst</option>
                        <option value="devops">DevOps Engineer</option>
                        <option value="qa_lead">QA Lead</option>
                    </select>
                </div>
            </div>
        `;

        div.querySelector('.remove-member').addEventListener('click', function() {
            div.remove();
            updateRemoveButtons();
            renumberMembers();
        });

        return div;
    }

    function updateRemoveButtons() {
        const members = document.querySelectorAll('.team-member');
        const removeButtons = document.querySelectorAll('.remove-member');

        if (members.length > 1) {
            removeButtons.forEach(btn => btn.style.display = 'block');
        } else {
            removeButtons.forEach(btn => btn.style.display = 'none');
        }
    }

    function renumberMembers() {
        const members = document.querySelectorAll('.team-member');
        members.forEach((member, index) => {
            const title = member.querySelector('h4');
            title.textContent = `Team Member ${index + 1}`;

            // Update input names
            const selects = member.querySelectorAll('select');
            selects.forEach(select => {
                const name = select.name;
                const field = name.split('[')[1].split(']')[0];
                select.name = `team_members[${index}][${field}]`;
            });
        });
        memberIndex = members.length;
    }

    // Initial setup
    updateRemoveButtons();
});
</script>
@endsection