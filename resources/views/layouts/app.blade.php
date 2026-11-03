<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventaris Aset') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body data-clear-draft="{{ session('draft_key') }}">
    @include('partials.navbar')

    <main class="container py-4 py-lg-5">
        @yield('content')
    </main>

    @include('partials.toasts')

    @stack('modals')
    @stack('scripts')
</body>
</html>
