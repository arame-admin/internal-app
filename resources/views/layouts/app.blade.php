<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - AraMeGlobal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .dropdown:hover .dropdown-menu {
            display: block !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
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
        $(document).ready(function() {
            $('select').each(function() {
                var $select = $(this);
                var optionCount = $select.find('option').length;

                $select.select2({
                    width: '100%',
                    minimumResultsForSearch: optionCount > 10 ? 0 : -1
                });
            });
        });
    </script>
</body>
</html>

