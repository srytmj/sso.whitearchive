@extends('layouts.dashboard')
@section('title', __('sessions.title'))
@section('heading', __('sessions.title'))

@section('content')
    @if(session('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 text-red-800 dark:text-red-300 text-sm px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- OAuth Sessions --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-3">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('sessions.oauth_heading') }}</h2>
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">{{ $oauthSessions->count() }}</span>
        </div>
        <p class="text-sm text-zinc-400 dark:text-zinc-500 mb-4">{{ __('sessions.oauth_description') }}</p>

        @if($oauthSessions->isEmpty())
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl px-6 py-10 text-center">
                <p class="text-sm text-zinc-400 dark:text-zinc-500">{{ __('sessions.oauth_empty') }}</p>
            </div>
        @else
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400 dark:text-zinc-500">{{ __('sessions.th_user') }}</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400 dark:text-zinc-500">{{ __('sessions.th_application') }}</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400 dark:text-zinc-500 hidden md:table-cell">{{ __('sessions.th_login_since') }}</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400 dark:text-zinc-500 hidden lg:table-cell">{{ __('sessions.th_expires') }}</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach($oauthSessions as $session)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $session->user_name }}</p>
                                    <p class="text-xs text-zinc-400 dark:text-zinc-500">{{ $session->user_email }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300">
                                        {{ $session->client_name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 hidden md:table-cell">
                                    {{ \Carbon\Carbon::parse($session->created_at)->diffForHumans() }}
                                </td>
                                <td class="px-4 py-3 hidden lg:table-cell">
                                    @php $expiresAt = \Carbon\Carbon::parse($session->expires_at); @endphp
                                    @if($expiresAt->diffInMinutes(now()) < 10 && $expiresAt->isFuture())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300">{{ __('sessions.expired_soon') }}</span>
                                    @else
                                        <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $expiresAt->diffForHumans() }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST"
                                        action="{{ route('dashboard.sessions.destroy', $session->id) }}?type=oauth"
                                        x-data
                                        @submit.prevent="if(confirm('{{ __('sessions.revoke_oauth_confirm', ['user' => $session->user_name, 'app' => $session->client_name]) }}')) $el.submit()">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium">{{ __('sessions.revoke') }}</button>
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
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('sessions.web_heading') }}</h2>
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">{{ $webSessions->count() }}</span>
        </div>
        <p class="text-sm text-zinc-400 dark:text-zinc-500 mb-4">{{ __('sessions.web_description') }}</p>

        @if($webSessions->isEmpty())
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl px-6 py-10 text-center">
                <p class="text-sm text-zinc-400 dark:text-zinc-500">{{ __('sessions.web_empty') }}</p>
            </div>
        @else
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400 dark:text-zinc-500">{{ __('sessions.th_user') }}</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400 dark:text-zinc-500 hidden md:table-cell">{{ __('sessions.th_ip') }}</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-zinc-400 dark:text-zinc-500 hidden lg:table-cell">{{ __('sessions.th_last_active') }}</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach($webSessions as $session)
                            @php $isSelf = $session->id === session()->getId(); @endphp
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div>
                                            <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $session->user_name }}</p>
                                            <p class="text-xs text-zinc-400 dark:text-zinc-500">{{ $session->user_email }}</p>
                                        </div>
                                        @if($isSelf)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400">{{ __('sessions.you') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 hidden md:table-cell">
                                    {{ $session->ip_address ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 hidden lg:table-cell">
                                    {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if($isSelf)
                                        <span class="text-xs text-zinc-300 dark:text-zinc-600 cursor-not-allowed" title="{{ __('sessions.revoke_self_disabled') }}">{{ __('sessions.revoke') }}</span>
                                    @else
                                        <form method="POST"
                                            action="{{ route('dashboard.sessions.destroy', $session->id) }}?type=web"
                                            x-data
                                            @submit.prevent="if(confirm('{{ __('sessions.revoke_web_confirm', ['user' => $session->user_name]) }}')) $el.submit()">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium">{{ __('sessions.revoke') }}</button>
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
