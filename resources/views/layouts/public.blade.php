<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SSO Engine — whitearchive.id')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-zinc-900 flex flex-col">
    <nav class="border-b border-zinc-100 px-4 sm:px-6 py-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between gap-4">
            <a href="/" class="font-semibold text-zinc-900 no-underline text-sm">whitearchive.id</a>
            <div class="flex items-center gap-3">
                @if($user ?? null)
                    @if($user->role?->slug === 'superadmin')
                        <a href="{{ route('dashboard.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900">Dashboard</a>
                    @else
                        <a href="{{ route('account.show') }}" class="text-sm text-zinc-600 hover:text-zinc-900">My Account</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-zinc-600 hover:text-zinc-900 bg-transparent border-0 cursor-pointer p-0">Sign Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-zinc-600 hover:text-zinc-900">Sign In</a>
                    <a href="{{ route('register') }}" class="text-sm bg-zinc-900 text-white px-3 py-1.5 rounded-lg hover:bg-zinc-800 transition-colors">Register</a>
                @endif
            </div>
        </div>
    </nav>
    <main class="flex-1">
        @yield('content')
    </main>
    <footer class="border-t border-zinc-100 py-8 mt-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-zinc-400">© {{ date('Y') }} whitearchive.id — SSO Engine</p>
            @if(!($user ?? null))
            <div class="flex items-center gap-6">
                <a href="{{ route('login') }}" class="text-sm text-zinc-400 hover:text-zinc-600">Sign In</a>
                <a href="{{ route('register') }}" class="text-sm text-zinc-400 hover:text-zinc-600">Register</a>
            </div>
            @endif
        </div>
    </footer>
</body>
</html>
