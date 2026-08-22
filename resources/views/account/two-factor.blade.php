@extends('layouts.account')

@section('title', __('two_factor.title'))

@section('content')
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6 mb-6">
        <div class="flex items-start justify-between mb-1">
            <div>
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('two_factor.title') }}</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">{{ __('two_factor.description') }}</p>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium shrink-0 {{ $user->hasTwoFactorEnabled() ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400' }}">
                {{ $user->hasTwoFactorEnabled() ? __('two_factor.status_enabled') : __('two_factor.status_disabled') }}
            </span>
        </div>

        <hr class="border-zinc-200 dark:border-zinc-800 my-4">

        @if($recoveryCodes)
            {{-- Recovery codes — shown once --}}
            <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-xl p-5 mb-4">
                <p class="text-xs font-semibold text-amber-800 dark:text-amber-200 mb-1">{{ __('two_factor.recovery_codes_heading') }}</p>
                <p class="text-xs text-amber-700 dark:text-amber-400 mb-3">{{ __('two_factor.recovery_codes_warning') }}</p>
                <div class="grid grid-cols-2 gap-2 font-mono text-sm text-zinc-800 dark:text-zinc-200">
                    @foreach($recoveryCodes as $code)
                        <code class="bg-white dark:bg-zinc-900 border border-amber-200 dark:border-amber-900 rounded-lg px-3 py-2">{{ $code }}</code>
                    @endforeach
                </div>
            </div>
        @endif

        @if($qrCodeSvg)
            {{-- Setup in progress: show QR + confirm form --}}
            <div class="max-w-sm">
                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-1">{{ __('two_factor.setup_heading') }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-4">{{ __('two_factor.setup_instruction') }}</p>
                {{-- bg-white sengaja fixed (bukan dark:bg-...) — QR code butuh kontras hitam-di-atas-putih
                     yang konsisten supaya kamera authenticator app bisa scan dengan andal --}}
                <div class="bg-white p-4 rounded-lg border border-zinc-200 dark:border-zinc-800 inline-block mb-3">
                    {!! $qrCodeSvg !!}
                </div>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-1">{{ __('two_factor.manual_key_label') }}</p>
                <code class="block text-xs font-mono text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800 rounded-lg px-3 py-2 mb-4 break-all">{{ $manualKey }}</code>

                <form method="POST" action="{{ route('account.two-factor.confirm') }}">
                    @csrf
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('two_factor.code_label') }}</label>
                    <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" autofocus
                        class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                    @error('code')
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="mt-4 inline-flex items-center justify-center px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">{{ __('two_factor.confirm_button') }}</button>
                </form>
            </div>
        @elseif($user->hasTwoFactorEnabled())
            <form method="POST" action="{{ route('account.two-factor.disable') }}" class="max-w-sm">
                @csrf @method('DELETE')
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('two_factor.confirm_password_label') }}</label>
                <input type="password" name="current_password"
                    class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                @error('current_password')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
                <button type="submit" class="mt-4 inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">{{ __('two_factor.disable_button') }}</button>
            </form>
        @else
            <form method="POST" action="{{ route('account.two-factor.enable') }}" class="max-w-sm">
                @csrf
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('two_factor.confirm_password_label') }}</label>
                <input type="password" name="current_password"
                    class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                @error('current_password')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
                <button type="submit" class="mt-4 inline-flex items-center justify-center px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">{{ __('two_factor.enable_button') }}</button>
            </form>
        @endif
    </div>
@endsection
