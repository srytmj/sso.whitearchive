@extends('layouts.dashboard')
@section('title', 'Active Sessions')
@section('heading', 'Active Sessions')

@section('content')
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- OAuth Sessions --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-3">
            <h2 class="text-sm font-semibold text-zinc-900">OAuth Sessions</h2>
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-zinc-100 text-zinc-600">{{ $oauthSessions->count() }}</span>
        </div>
        <p class="text-sm text-zinc-400 mb-4">User yang sedang aktif menggunakan aplikasi via SSO.</p>

        @if($oauthSessions->isEmpty())
            <div class="bg-white border border-zinc-200 rounded-xl px-6 py-10 text-center">
                <p class="text-sm text-zinc-400">Tidak ada OAuth session aktif.</p>
            </div>
        @else
            <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-100">
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400">User</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400">Aplikasi</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400 hidden md:table-cell">Login sejak</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400 hidden lg:table-cell">Expires</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach($oauthSessions as $session)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-zinc-900">{{ $session->user_name }}</p>
                                    <p class="text-xs text-zinc-400">{{ $session->user_email }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700">
                                        {{ $session->client_name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-zinc-500 hidden md:table-cell">
                                    {{ \Carbon\Carbon::parse($session->created_at)->diffForHumans() }}
                                </td>
                                <td class="px-4 py-3 hidden lg:table-cell">
                                    @php $expiresAt = \Carbon\Carbon::parse($session->expires_at); @endphp
                                    @if($expiresAt->diffInMinutes(now()) < 10 && $expiresAt->isFuture())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-amber-50 text-amber-700">Expired soon</span>
                                    @else
                                        <span class="text-xs text-zinc-400">{{ $expiresAt->diffForHumans() }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST"
                                        action="{{ route('dashboard.sessions.destroy', $session->id) }}?type=oauth"
                                        x-data
                                        @submit.prevent="if(confirm('Cabut OAuth session {{ $session->user_name }} di {{ $session->client_name }}?')) $el.submit()">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium">Cabut</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- SSO Web Sessions --}}
    <div>
        <div class="flex items-center gap-3 mb-3">
            <h2 class="text-sm font-semibold text-zinc-900">SSO Web Sessions</h2>
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-zinc-100 text-zinc-600">{{ $webSessions->count() }}</span>
        </div>
        <p class="text-sm text-zinc-400 mb-4">User yang sedang membuka sso.suryatmaja.dev.</p>

        @if($webSessions->isEmpty())
            <div class="bg-white border border-zinc-200 rounded-xl px-6 py-10 text-center">
                <p class="text-sm text-zinc-400">Tidak ada SSO web session aktif.</p>
            </div>
        @else
            <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-100">
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400">User</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400 hidden md:table-cell">IP</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400 hidden lg:table-cell">Terakhir aktif</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach($webSessions as $session)
                            @php $isSelf = $session->id === session()->getId(); @endphp
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div>
                                            <p class="font-medium text-zinc-900">{{ $session->user_name }}</p>
                                            <p class="text-xs text-zinc-400">{{ $session->user_email }}</p>
                                        </div>
                                        @if($isSelf)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-zinc-100 text-zinc-500">Kamu</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-zinc-500 hidden md:table-cell">
                                    {{ $session->ip_address ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-zinc-500 hidden lg:table-cell">
                                    {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if($isSelf)
                                        <span class="text-xs text-zinc-300 cursor-not-allowed" title="Tidak bisa mencabut session sendiri">Cabut</span>
                                    @else
                                        <form method="POST"
                                            action="{{ route('dashboard.sessions.destroy', $session->id) }}?type=web"
                                            x-data
                                            @submit.prevent="if(confirm('Cabut web session {{ $session->user_name }}? User akan di-logout.')) $el.submit()">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium">Cabut</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
