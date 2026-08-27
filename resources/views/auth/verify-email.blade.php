@extends('layouts.auth')

@section('title', __('auth.verify_email_title'))
@section('subtitle', __('auth.verify_email_subtitle'))

@section('content')
    @if(session('status'))
        <div class="alert alert-success alert-soft mb-6 text-sm animate-slide-up" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex flex-col items-center text-center mb-6">
        <svg class="w-10 h-10 text-base-content/30 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
        </svg>
        <p class="text-sm text-base-content/60">{{ __('auth.verify_email_instruction') }}</p>
    </div>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-neutral w-full active:scale-95">{{ __('auth.resend_verification_button') }}</button>
    </form>

    <div class="divider my-6"></div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-ghost btn-sm w-full text-base-content/60 hover:text-base-content">{{ __('common.sign_out') }}</button>
    </form>
@endsection
