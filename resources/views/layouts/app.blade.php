<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - AraMeGlobal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selects = document.querySelectorAll('select');
            selects.forEach(function(select) {
                if (select.options.length > 0) {
                    new Choices(select, {
                        width: '100%',
                        searchEnabled: select.options.length > 10
                    });
                }
            });
        });
    </script>
</body>
</html>

