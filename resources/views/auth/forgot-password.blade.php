@extends('layouts.auth')

@section('title', __('auth.forgot_password_title'))
@section('subtitle', __('auth.forgot_password_subtitle'))

@section('content')
    @if(session('status'))
        <div class="alert alert-success alert-soft mb-6 text-sm animate-slide-up" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <p class="text-sm text-base-content/60 mb-6">
        {{ __('auth.forgot_password_instruction') }}
    </p>

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('auth.email_address') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" autofocus
                    class="input input-bordered w-full" />
                @error('email')
                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-neutral w-full active:scale-95">{{ __('auth.send_reset_link_button') }}</button>
        </div>
    </form>

    <div class="divider my-6"></div>

    <p class="text-sm text-center text-base-content/60">
        {{ __('auth.remember_password') }}
        <a href="{{ route('login') }}" class="text-base-content font-medium hover:underline">{{ __('auth.login_link') }}</a>
    </p>
@endsection
