<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Structure</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .toggle-icon {
            transition: transform 0.2s ease;
        }
        .toggle-icon.collapsed {
            transform: rotate(-90deg);
        }
        .child-container {
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            max-height: 0;
        }
        .child-container:not(.collapsed) {
            max-height: 5000px;
        }
        .hierarchy-line {
            position: absolute;
            left: 11px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #bfdbfe, #e5e7eb);
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('components.header')
    
    <div class="pt-20 pb-12 px-6">
        <div class="max-w-5xl mx-auto">
            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Team Structure</h1>
                <p class="mt-1 text-gray-600">View the organizational hierarchy and team members</p>
            </div>

            <!-- Team Tree -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Organization Hierarchy</h2>
                        <div class="flex items-center space-x-3">
                            <button onclick="expandAll()" class="text-xs text-blue-600 hover:text-blue-700 font-medium flex items-center">
                                <i class="fas fa-plus-square mr-1"></i> Expand All
                            </button>
                            <button onclick="collapseAll()" class="text-xs text-gray-500 hover:text-gray-700 font-medium flex items-center">
                                <i class="fas fa-minus-square mr-1"></i> Collapse All
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if(count($teamTree) > 0)
                        <div class="relative">
                            @foreach($teamTree as $manager)
                                @include('Admin.team.partials.tree-item', ['item' => $manager, 'level' => 0])
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-sitemap text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No Team Data</h3>
                            <p class="text-gray-500">No team members found. Please add users with reporting managers.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Legend -->
            <div class="mt-4 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center space-x-6 flex-wrap">
                    <span class="text-xs text-gray-500 font-medium">Legend:</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-xs text-gray-600">Manager (has team)</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                        <span class="text-xs text-gray-600">Team Member</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        <span class="text-xs text-gray-600">Click to expand/collapse</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleBranch(button) {
            const container = button.closest('.team-item').querySelector('.child-container');
            const icon = button.querySelector('.toggle-icon');
            
            if (container) {
                container.classList.toggle('collapsed');
                icon.classList.toggle('collapsed');
            }
        }

        function expandAll() {
            document.querySelectorAll('.child-container').forEach(container => {
                container.classList.remove('collapsed');
            });
            document.querySelectorAll('.toggle-icon').forEach(icon => {
                icon.classList.remove('collapsed');
            });
        }

        function collapseAll() {
            document.querySelectorAll('.child-container').forEach(container => {
                container.classList.add('collapsed');
            });
            document.querySelectorAll('.toggle-icon').forEach(icon => {
                icon.classList.add('collapsed');
            });
        }
    </script>
</body>
</html>
