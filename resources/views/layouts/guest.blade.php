<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon_io/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon_io/favicon-16x16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon_io/apple-touch-icon.png') }}">
        <link rel="manifest" href="{{ asset('manifest.json') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- PWA Service Worker -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then((registration) => {
                            console.log('Service Worker registered:', registration);
                        })
                        .catch((error) => {
                            console.log('Service Worker registration failed:', error);
                        });
                });
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Mobile: Fixed height, no scroll -->
        <div class="md:hidden h-screen flex flex-col items-center justify-center pt-8 px-4 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 overflow-hidden">
            <div class="mb-4 text-center flex-shrink-0">
                <div class="mb-3">
                    <a href="/" class="block transform transition-transform hover:scale-110 active:scale-95">
                        <x-application-logo class="w-48 h-[196px] mx-auto drop-shadow-lg" />
                    </a>
                </div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent mb-1">
                    Absensi Bank Jogja
                </h1>
                <p class="text-sm text-gray-500 font-medium">
                    v 1.0
                </p>
            </div>

            <div class="w-full mt-2 px-4 py-5 bg-white shadow-xl overflow-hidden rounded-xl border border-gray-100 flex-shrink-0">
                {{ $slot }}
            </div>
        </div>

        <!-- Desktop: Normal layout with scroll if needed -->
        <div class="hidden md:flex min-h-screen flex-col justify-center items-center px-6 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
            <div class="mb-8 text-center">
                <div class="mb-6">
                    <a href="/" class="block transform transition-transform hover:scale-110">
                        <x-application-logo class="w-64 h-64 mx-auto drop-shadow-lg" />
                    </a>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                    Absensi Bank Jogja
                </h1>
                <p class="text-lg text-gray-500 font-medium">
                    v 1.0
                </p>
            </div>

            <div class="w-full max-w-lg px-8 py-10 bg-white shadow-xl overflow-hidden rounded-2xl border border-gray-100 transform transition-all hover:shadow-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
