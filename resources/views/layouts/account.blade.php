<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Account') — SSO Engine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-50">
    {{-- Mobile tab nav --}}
    <div class="md:hidden bg-white border-b border-zinc-200">
        <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-100">
            <a href="/" class="font-semibold text-zinc-900 text-sm">whitearchive.id</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-zinc-500 hover:text-zinc-700">Sign Out</button>
            </form>
        </div>
        <div class="flex overflow-x-auto px-4">
            <a href="{{ route('account.show') }}"
               class="shrink-0 text-sm px-1 py-3 mr-6 border-b-2 {{ request()->routeIs('account.show') ? 'border-zinc-900 text-zinc-900 font-medium' : 'border-transparent text-zinc-500' }}">
                My Account
            </a>
            <a href="{{ route('account.sessions') }}"
               class="shrink-0 text-sm px-1 py-3 border-b-2 {{ request()->routeIs('account.sessions') ? 'border-zinc-900 text-zinc-900 font-medium' : 'border-transparent text-zinc-500' }}">
                Active Sessions
            </a>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
        <div class="flex gap-10">
            {{-- Sidebar desktop --}}
            <aside class="hidden md:block w-44 shrink-0">
                <a href="/" class="block font-semibold text-sm text-zinc-900 mb-5">whitearchive.id</a>
                <nav class="space-y-1 mb-6">
                    <a href="{{ route('account.show') }}"
                       class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('account.show') ? 'bg-zinc-100 text-zinc-900 font-medium' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                        My Account
                    </a>
                    <a href="{{ route('account.sessions') }}"
                       class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('account.sessions') ? 'bg-zinc-100 text-zinc-900 font-medium' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                        Active Sessions
                    </a>
                </nav>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-zinc-400 hover:text-zinc-600 px-3 py-1">Sign Out</button>
                </form>
            </aside>

            <main class="flex-1 min-w-0">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
