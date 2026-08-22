<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — {{ __('common.app_name') }}</title>
    @include('partials.theme-init')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 flex items-center justify-center px-4 py-10">
    <div class="absolute top-4 right-4">
        @include('partials.theme-toggle')
    </div>
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">whitearchive.id</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">@yield('subtitle')</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl px-6 py-8 sm:px-8 sm:py-10 shadow-sm">
            @yield('content')
        </div>
    </div>
</body>
</html>
