@extends('layouts.auth')

@section('title', __('auth.forgot_password_title'))
@section('subtitle', __('auth.forgot_password_subtitle'))

@section('content')
    @if(session('status'))
        <div class="flex items-start gap-3 p-4 rounded-lg bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-900 text-green-800 dark:text-green-300 text-sm mb-6">
            {{ session('status') }}
        </div>
    @endif

    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
        {{ __('auth.forgot_password_instruction') }}
    </p>

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('auth.email_address') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" autofocus
                    class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                @error('email')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">{{ __('auth.send_reset_link_button') }}</button>
        </div>
    </form>

    <hr class="border-zinc-200 dark:border-zinc-800 my-6">

    <p class="text-sm text-center text-zinc-500 dark:text-zinc-400">
        {{ __('auth.remember_password') }}
        <a href="{{ route('login') }}" class="text-zinc-900 dark:text-zinc-100 font-medium hover:underline">{{ __('auth.login_link') }}</a>
    </p>
@endsection
