<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('common.app_name') . ' — whitearchive.id')</title>
    @include('partials.theme-init', ['theme' => auth()->user()?->theme])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 flex flex-col">
    <nav class="border-b border-zinc-100 dark:border-zinc-800 px-4 sm:px-6 py-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between gap-4">
            <a href="/" class="font-semibold text-zinc-900 dark:text-zinc-100 no-underline text-sm">whitearchive.id</a>
            <div class="flex items-center gap-3">
                @include('partials.theme-toggle')
                @if($user ?? null)
                    @if($user->role?->slug === 'superadmin')
                        <a href="{{ route('dashboard.index') }}" class="text-sm text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white">{{ __('dashboard.nav_overview') }}</a>
                    @else
                        <a href="{{ route('account.show') }}" class="text-sm text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white">{{ __('account.title') }}</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white bg-transparent border-0 cursor-pointer p-0">{{ __('common.sign_out') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white">{{ __('common.sign_in') }}</a>
                    <a href="{{ route('register') }}" class="text-sm bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 px-3 py-1.5 rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">{{ __('common.register') }}</a>
                @endif
            </div>
        </div>
    </nav>
    <main class="flex-1">
        @yield('content')
    </main>
    <footer class="border-t border-zinc-100 dark:border-zinc-800 py-8 mt-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-zinc-400 dark:text-zinc-500">© {{ date('Y') }} whitearchive.id — {{ __('common.app_name') }}</p>
            @if(!($user ?? null))
            <div class="flex items-center gap-6">
                <a href="{{ route('login') }}" class="text-sm text-zinc-400 dark:text-zinc-500 hover:text-zinc-600 dark:hover:text-zinc-300">{{ __('common.sign_in') }}</a>
                <a href="{{ route('register') }}" class="text-sm text-zinc-400 dark:text-zinc-500 hover:text-zinc-600 dark:hover:text-zinc-300">{{ __('common.register') }}</a>
            </div>
            @endif
        </div>
    </footer>
</body>
</html>
