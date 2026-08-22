@extends('layouts.auth')

@section('title', __('two_factor.challenge_title'))
@section('subtitle', __('two_factor.challenge_subtitle'))

@section('content')
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">{{ __('two_factor.challenge_instruction') }}</p>

    <form method="POST" action="{{ route('two-factor.verify') }}" novalidate>
        @csrf
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('two_factor.code_label') }}</label>
                <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" autofocus
                    class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                @error('code')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">{{ __('two_factor.verify_button') }}</button>
        </div>
    </form>
@endsection
