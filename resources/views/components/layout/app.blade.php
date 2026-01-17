<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ config('site.description') }}">
    <meta name="theme-color" content="#131720">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @production
        <meta http-equiv="Content-Security-Policy"
            content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' https://image.tmdb.org https://s4.anilist.co data:; frame-src https://www.youtube.com; connect-src 'self';">
    @endproduction
    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Fonts (optimized loading) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Rubik:wght@500;600;700&display=swap">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Rubik:wght@500;600;700&display=swap"
        rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Rubik:wght@500;600;700&display=swap"
            rel="stylesheet">
    </noscript>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-bg-main text-text-main font-sans antialiased min-h-screen flex flex-col">
    {{-- Skip Link for Accessibility --}}
    <a href="#main-content"
        class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-toast focus:bg-primary focus:text-white focus:px-4 focus:py-2 focus:rounded-lg focus:outline-none focus:ring-2 focus:ring-white">
        Ana içeriğe atla
    </a>

    <x-layout.navbar />

    <main id="main-content" class="flex-grow">
        {{ $slot }}
    </main>

    <x-layout.footer />

    <x-ui.toast />
    @livewireScripts
</body>

</html>