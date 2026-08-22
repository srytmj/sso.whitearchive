@extends('layouts.auth')

@section('title', __('auth.verify_email_title'))
@section('subtitle', __('auth.verify_email_subtitle'))

@section('content')
    @if(session('status'))
        <div class="flex items-start gap-3 p-4 rounded-lg bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-900 text-green-800 dark:text-green-300 text-sm mb-6">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex flex-col items-center text-center mb-6">
        <svg class="w-10 h-10 text-zinc-300 dark:text-zinc-700 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
        </svg>
        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('auth.verify_email_instruction') }}</p>
    </div>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">{{ __('auth.resend_verification_button') }}</button>
    </form>

    <hr class="border-zinc-200 dark:border-zinc-800 my-6">

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-sm text-center text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100">{{ __('common.sign_out') }}</button>
    </form>
@endsection
