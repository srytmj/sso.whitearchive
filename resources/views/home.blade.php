@extends('layouts.public')

@section('title', 'SSO Engine — whitearchive.id')

@section('content')
    <div class="max-w-5xl mx-auto px-6 py-16 sm:py-24">

        {{-- Hero --}}
        <div class="text-center mb-16 sm:mb-24">
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-700 mb-6">OAuth2 + PKCE</span>
            <h2 class="text-4xl font-bold text-zinc-900 mb-4 leading-tight">SSO Engine</h2>
            <p class="text-lg text-zinc-500 max-w-xl mx-auto mb-8 leading-relaxed">
                Identity Provider terpusat untuk ekosistem whitearchive.id.<br class="hidden sm:block">
                Login sekali, akses semua aplikasi.
            </p>

            @if($user ?? null)
                <div class="flex items-center justify-center gap-3">
                    @if($user->role?->slug === 'superadmin')
                        <a href="{{ route('dashboard.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-800 transition-colors">Buka Dashboard</a>
                    @else
                        <a href="{{ route('account.show') }}" class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-800 transition-colors">Lihat Akun Saya</a>
                    @endif
                </div>
            @else
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-800 transition-colors">Sign In</a>
                    <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-zinc-600 text-sm font-medium rounded-lg hover:bg-zinc-100 transition-colors">Daftar Sekarang</a>
                </div>
            @endif
        </div>

        {{-- Features --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white border border-zinc-200 rounded-xl p-6">
                <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 mb-2">Single Sign-On</h3>
                <p class="text-sm text-zinc-500 leading-relaxed">Login sekali, langsung masuk ke semua aplikasi ekosistem tanpa perlu input password lagi.</p>
            </div>

            <div class="bg-white border border-zinc-200 rounded-xl p-6">
                <div class="w-9 h-9 bg-violet-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 mb-2">OAuth2 + PKCE</h3>
                <p class="text-sm text-zinc-500 leading-relaxed">Standar industri Authorization Code flow dengan PKCE untuk keamanan maksimal di setiap exchange.</p>
            </div>

            <div class="bg-white border border-zinc-200 rounded-xl p-6">
                <div class="w-9 h-9 bg-emerald-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" /></svg>
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 mb-2">Self-service Register</h3>
                <p class="text-sm text-zinc-500 leading-relaxed">Daftar akun sekali, langsung bisa akses Malas, Scribe, dan semua aplikasi whitearchive.</p>
            </div>

            <div class="bg-white border border-zinc-200 rounded-xl p-6">
                <div class="w-9 h-9 bg-amber-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 mb-2">Secure Token</h3>
                <p class="text-sm text-zinc-500 leading-relaxed">Access token 60 menit, refresh token 30 hari. Revoke kapan saja dari halaman akun.</p>
            </div>
        </div>
    </div>
@endsection
