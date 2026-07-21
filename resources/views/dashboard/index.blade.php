@extends('layouts.dashboard')
@section('title', 'Overview')
@section('heading', 'Overview')

@section('content')
    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-zinc-200 rounded-xl p-6">
            <p class="text-xs text-zinc-400 mb-1">Active Users</p>
            <p class="text-3xl font-bold text-zinc-900">{{ $stats['users_active'] }}</p>
            <p class="text-xs text-zinc-400 mt-1">of {{ $stats['users_total'] }} total</p>
        </div>
        <div class="bg-white border border-zinc-200 rounded-xl p-6">
            <p class="text-xs text-zinc-400 mb-1">Registered Apps</p>
            <p class="text-3xl font-bold text-zinc-900">{{ $stats['clients'] }}</p>
        </div>
        <div class="bg-white border border-zinc-200 rounded-xl p-6">
            <p class="text-xs text-zinc-400 mb-1">OAuth2 Standard</p>
            <p class="text-sm font-semibold text-blue-600 mt-1">Auth Code + PKCE</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Registered Apps --}}
        <div class="bg-white border border-zinc-200 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-zinc-900">Registered Applications</h2>
                <a href="{{ route('dashboard.applications.create') }}" class="text-sm text-zinc-600 hover:text-zinc-900">+ Add</a>
            </div>

            @if($clients->isEmpty())
                <p class="text-sm text-zinc-400">No applications registered yet.</p>
            @else
                <div class="space-y-2">
                    @foreach($clients as $client)
                        <div class="flex items-center justify-between py-2 border-b border-zinc-50 last:border-0">
                            <div class="min-w-0 mr-4">
                                <p class="text-sm font-medium text-zinc-900 truncate">{{ $client->name }}</p>
                                <p class="text-xs text-zinc-400 font-mono mt-0.5 truncate">{{ str()->limit($client->redirect, 40) }}</p>
                            </div>
                            <a href="{{ route('dashboard.applications.show', $client->id) }}" class="text-xs text-zinc-600 hover:text-zinc-900 shrink-0">Detail</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white border border-zinc-200 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-zinc-900 mb-4">Quick Actions</h2>
            <div class="space-y-1">
                <a href="{{ route('dashboard.applications.create') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 rounded-lg">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Register New App
                </a>
                <a href="{{ route('dashboard.applications.index') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 rounded-lg">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3" /></svg>
                    Manage Applications
                </a>
                <a href="{{ route('dashboard.users.invite') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 rounded-lg">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" /></svg>
                    Invite User
                </a>
                <a href="{{ route('dashboard.users.index') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 rounded-lg">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                    Manage Users
                </a>
            </div>
        </div>
    </div>
@endsection
