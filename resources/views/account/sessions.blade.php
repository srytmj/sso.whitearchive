@extends('layouts.account')

@section('title', 'Active Sessions')

@section('content')
    <div class="bg-white border border-zinc-200 rounded-xl p-6">
        <div class="flex items-start justify-between mb-1">
            <div>
                <h2 class="text-sm font-semibold text-zinc-900">Active Sessions</h2>
                <p class="text-sm text-zinc-500 mt-0.5">OAuth access token yang aktif untuk akun ini</p>
            </div>
            @if($tokens->isNotEmpty())
                <form method="POST" action="{{ route('account.sessions.revoke-all') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Cabut semua session aktif?')"
                        class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        Revoke All
                    </button>
                </form>
            @endif
        </div>

        <hr class="border-zinc-200 my-4">

        @if($tokens->isEmpty())
            <div class="py-8 text-center">
                <svg class="w-8 h-8 text-zinc-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                <p class="text-sm text-zinc-400">Tidak ada session OAuth aktif.</p>
                <p class="text-xs text-zinc-400 mt-1">Session muncul setelah kamu login ke aplikasi ekosistem via SSO.</p>
            </div>
        @else
            <div class="space-y-1">
                @foreach($tokens as $token)
                    <div class="flex items-start sm:items-center justify-between gap-4 py-3 border-b border-zinc-50 last:border-0">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-zinc-900 truncate">
                                {{ $token->client?->name ?? 'Unknown App' }}
                            </p>
                            <p class="text-xs text-zinc-400 mt-0.5">
                                Dibuat {{ $token->created_at?->diffForHumans() ?? '—' }}
                                @if($token->expires_at)
                                    · Expired {{ \Carbon\Carbon::parse($token->expires_at)->diffForHumans() }}
                                @endif
                            </p>
                            @if(!empty($token->scopes))
                                <div class="flex flex-wrap gap-1 mt-1.5">
                                    @foreach($token->scopes as $scope)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-zinc-100 text-zinc-600">{{ $scope }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('account.sessions.revoke', $token->id) }}" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Cabut session ini?')"
                                class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                Revoke
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
