@extends('layouts.account')

@section('title', __('sessions.title'))

@section('content')
    {{-- My Devices --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6 mb-6">
        <div class="flex items-start justify-between mb-1">
            <div>
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('sessions.devices_heading') }}</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">{{ __('sessions.devices_description') }}</p>
            </div>
            @if($devices->count() > 1)
                <form method="POST" action="{{ route('account.devices.revoke-all') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('{{ __('sessions.logout_all_others_confirm') }}')"
                        class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        {{ __('sessions.logout_all_others') }}
                    </button>
                </form>
            @endif
        </div>

        <hr class="border-zinc-200 dark:border-zinc-800 my-4">

        @if($devices->isEmpty())
            <p class="text-sm text-zinc-400 dark:text-zinc-500 text-center py-4">{{ __('sessions.devices_empty') }}</p>
        @else
            <div class="space-y-1">
                @foreach($devices as $device)
                    @php $isSelf = $device->id === $currentSessionId; @endphp
                    <div class="flex items-center justify-between gap-4 py-3 border-b border-zinc-50 dark:border-zinc-800 last:border-0">
                        <div class="min-w-0 flex items-center gap-2">
                            <div>
                                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ \App\Support\UserAgentParser::label($device->user_agent) }}</p>
                                <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">
                                    {{ $device->ip_address ?? '—' }} · {{ \Carbon\Carbon::createFromTimestamp($device->last_activity)->diffForHumans() }}
                                </p>
                            </div>
                            @if($isSelf)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 shrink-0">{{ __('sessions.you') }}</span>
                            @endif
                        </div>
                        @if($isSelf)
                            <span class="text-xs text-zinc-300 dark:text-zinc-600 cursor-not-allowed shrink-0">{{ __('sessions.revoke') }}</span>
                        @else
                            <form method="POST" action="{{ route('account.devices.revoke', $device->id) }}" class="shrink-0">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('{{ __('sessions.device_revoke_confirm') }}')"
                                    class="text-xs text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium">
                                    {{ __('sessions.revoke') }}
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
        <div class="flex items-start justify-between mb-1">
            <div>
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('sessions.account_heading') }}</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">{{ __('sessions.account_description') }}</p>
            </div>
            @if($tokens->isNotEmpty())
                <form method="POST" action="{{ route('account.sessions.revoke-all') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('{{ __('sessions.revoke_all_confirm') }}')"
                        class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        {{ __('sessions.revoke_all') }}
                    </button>
                </form>
            @endif
        </div>

        <hr class="border-zinc-200 dark:border-zinc-800 my-4">

        @if($tokens->isEmpty())
            <div class="py-8 text-center">
                <svg class="w-8 h-8 text-zinc-300 dark:text-zinc-700 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                <p class="text-sm text-zinc-400 dark:text-zinc-500">{{ __('sessions.account_empty') }}</p>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">{{ __('sessions.account_empty_hint') }}</p>
            </div>
        @else
            <div class="space-y-1">
                @foreach($tokens as $token)
                    <div class="flex items-start sm:items-center justify-between gap-4 py-3 border-b border-zinc-50 dark:border-zinc-800 last:border-0">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">
                                {{ $token->client?->name ?? __('sessions.unknown_app') }}
                            </p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">
                                {{ __('sessions.created', ['time' => $token->created_at?->diffForHumans() ?? '—']) }}
                                @if($token->expires_at)
                                    · {{ __('sessions.expired', ['time' => \Carbon\Carbon::parse($token->expires_at)->diffForHumans()]) }}
                                @endif
                            </p>
                            @if(!empty($token->scopes))
                                <div class="flex flex-wrap gap-1 mt-1.5">
                                    @foreach($token->scopes as $scope)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">{{ $scope }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('account.sessions.revoke', $token->id) }}" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('{{ __('sessions.revoke_confirm') }}')"
                                class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                {{ __('sessions.revoke') }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
