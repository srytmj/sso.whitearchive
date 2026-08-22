<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('account.title')) — {{ __('common.app_name') }}</title>
    @include('partials.theme-init', ['theme' => auth()->user()?->theme])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
    {{-- Mobile tab nav --}}
    <div class="md:hidden bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800">
        <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-100 dark:border-zinc-800">
            <a href="/" class="font-semibold text-zinc-900 dark:text-zinc-100 text-sm">whitearchive.id</a>
            <div class="flex items-center gap-2">
                @include('partials.theme-toggle')
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200">{{ __('common.sign_out') }}</button>
                </form>
            </div>
        </div>
        <div class="flex overflow-x-auto px-4">
            <a href="{{ route('account.show') }}"
               class="shrink-0 text-sm px-1 py-3 mr-6 border-b-2 {{ request()->routeIs('account.show') ? 'border-zinc-900 dark:border-zinc-100 text-zinc-900 dark:text-zinc-100 font-medium' : 'border-transparent text-zinc-500 dark:text-zinc-400' }}">
                {{ __('account.nav_account') }}
            </a>
            <a href="{{ route('account.sessions') }}"
               class="shrink-0 text-sm px-1 py-3 border-b-2 {{ request()->routeIs('account.sessions') ? 'border-zinc-900 dark:border-zinc-100 text-zinc-900 dark:text-zinc-100 font-medium' : 'border-transparent text-zinc-500 dark:text-zinc-400' }}">
                {{ __('account.nav_sessions') }}
            </a>
            <a href="{{ route('account.two-factor.show') }}"
               class="shrink-0 text-sm px-1 py-3 border-b-2 {{ request()->routeIs('account.two-factor.*') ? 'border-zinc-900 dark:border-zinc-100 text-zinc-900 dark:text-zinc-100 font-medium' : 'border-transparent text-zinc-500 dark:text-zinc-400' }}">
                {{ __('two_factor.title') }}
            </a>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
        <div class="flex gap-10">
            {{-- Sidebar desktop --}}
            <aside class="hidden md:block w-44 shrink-0">
                <div class="flex items-center justify-between mb-5">
                    <a href="/" class="block font-semibold text-sm text-zinc-900 dark:text-zinc-100">whitearchive.id</a>
                    @include('partials.theme-toggle')
                </div>
                <nav class="space-y-1 mb-6">
                    <a href="{{ route('account.show') }}"
                       class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('account.show') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-50 dark:hover:bg-zinc-800/60' }}">
                        {{ __('account.nav_account') }}
                    </a>
                    <a href="{{ route('account.sessions') }}"
                       class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('account.sessions') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-50 dark:hover:bg-zinc-800/60' }}">
                        {{ __('account.nav_sessions') }}
                    </a>
                    <a href="{{ route('account.two-factor.show') }}"
                       class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('account.two-factor.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-50 dark:hover:bg-zinc-800/60' }}">
                        {{ __('two_factor.title') }}
                    </a>
                </nav>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-zinc-400 dark:text-zinc-500 hover:text-zinc-600 dark:hover:text-zinc-300 px-3 py-1">{{ __('common.sign_out') }}</button>
                </form>
            </aside>

            <main class="flex-1 min-w-0">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-900 text-green-800 dark:text-green-300 text-sm px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 text-red-800 dark:text-red-300 text-sm px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
