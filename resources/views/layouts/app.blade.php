<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - AraMeGlobal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <style>
        .dropdown:hover .dropdown-menu {
            display: block !important;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header Component -->
    @include('components.header')

    <!-- Flash Messages -->
    @include('components.flash-message')

    <!-- Main Content -->
    <main class="pt-24 p-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 border-t border-gray-200 py-4 mt-12">
        <div class="text-center text-sm text-gray-500">
            © 2026 ArameGlobal. All rights reserved.
        </div>
    </footer>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selects = document.querySelectorAll('select:not([id="task"])');
            selects.forEach(function(select) {
                if (select.options.length > 0 && typeof Choices !== 'undefined') {
                    try {
                        const forceSearch = select.classList.contains('enable-search') || select.dataset.enableSearch === 'true';
                        new Choices(select, {
                            searchEnabled: forceSearch || select.options.length > 5
                        });
                    } catch (e) {
                        console.warn('Choices.js failed to initialize for select:', select.id || select.name, e);
                    }
                }
            });
            
            // Function to initialize datepickers on date inputs
            function initDatePickers() {
                if (typeof $ !== 'undefined' && typeof $.fn.datepicker !== 'undefined') {
                    $('input[type="date"]').each(function() {
                        // Skip if already initialized
                        if (!$(this).hasClass('hasDatepicker')) {
                            $(this).datepicker({
                                dateFormat: 'yy-mm-dd',
                                changeMonth: true,
                                changeYear: true,
                                yearRange: '1950:2100'
                            });
                        }
                    });
                }
            }
            
            // Initialize datepickers on page load
            initDatePickers();
            
            // Watch for dynamically added date inputs using MutationObserver
            if (typeof MutationObserver !== 'undefined') {
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1) { // Element node
                                // Check if the added node is a date input
                                if (node.matches && node.matches('input[type="date"]')) {
                                    initDatePickers();
                                }
                                // Check for date inputs within added container
                                const dateInputs = node.querySelectorAll && node.querySelectorAll('input[type="date"]');
                                if (dateInputs && dateInputs.length > 0) {
                                    initDatePickers();
                                }
                            }
                        });
                    });
                });
                
                observer.observe(document.body, { 
                    childList: true, 
                    subtree: true 
                });
            }
        });
    </script>
</body>
</html>

