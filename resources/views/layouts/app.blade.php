<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - AraMeGlobal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <main class="pt-20 p-8">
        @yield('content')
    </main>
</body>
</html>

