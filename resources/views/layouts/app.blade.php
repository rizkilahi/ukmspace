<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="UKMSpace - Your Ultimate College Community Event Partner">
    <meta name="author" content="UKMSpace Team">
    <meta name="keywords" content="UKM, Events, College, Community">

    @stack('meta')

    <title>@yield('title', 'UKMSpace')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>

<body class="bg-light text-dark">
    <!-- Navigation -->
    @include('layouts.navigation')

    <!-- Page Wrapper -->
    <div class="min-vh-100 d-flex flex-column">
        <!-- Page Header -->
        @if (isset($header))
            <header class="bg-white shadow-sm mb-4">
                <div class="container py-4">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="flex-fill">
            <div class="container my-4">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        @include('layouts.footer')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
