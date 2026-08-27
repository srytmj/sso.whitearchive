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
<body class="min-h-screen bg-base-100 text-base-content flex flex-col">
    <nav class="navbar border-b border-base-200 px-4 sm:px-6 py-4">
        <div class="max-w-5xl mx-auto w-full flex items-center justify-between gap-4">
            <a href="/" class="font-semibold text-base-content no-underline text-sm">whitearchive.id</a>
            <div class="flex items-center gap-3">
                @include('partials.theme-toggle')
                @if($user ?? null)
                    @if($user->role?->slug === 'superadmin')
                        <a href="{{ route('dashboard.index') }}" class="link link-hover text-sm text-base-content/70 hover:text-base-content">{{ __('dashboard.nav_overview') }}</a>
                    @else
                        <a href="{{ route('account.show') }}" class="link link-hover text-sm text-base-content/70 hover:text-base-content">{{ __('account.title') }}</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm text-base-content/70 hover:text-base-content">{{ __('common.sign_out') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="link link-hover text-sm text-base-content/70 hover:text-base-content">{{ __('common.sign_in') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-neutral btn-sm active:scale-95">{{ __('common.register') }}</a>
                @endif
            </div>
        </div>
    </nav>
    <main class="flex-1">
        @yield('content')
    </main>
    <footer class="border-t border-base-200 py-8 mt-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-base-content/40">© {{ date('Y') }} whitearchive.id — {{ __('common.app_name') }}</p>
            @if(!($user ?? null))
            <div class="flex items-center gap-6">
                <a href="{{ route('login') }}" class="link link-hover text-sm text-base-content/40 hover:text-base-content/70">{{ __('common.sign_in') }}</a>
                <a href="{{ route('register') }}" class="link link-hover text-sm text-base-content/40 hover:text-base-content/70">{{ __('common.register') }}</a>
            </div>
            @endif
        </div>
    </footer>
</body>
</html>
