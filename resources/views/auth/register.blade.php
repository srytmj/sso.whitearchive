@extends('layouts.auth')

@section('title', __('auth.register_title'))
@section('subtitle', __('auth.register_subtitle'))

@section('content')
    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-base-content/70 mb-1">
                    {{ __('auth.full_name') }}
                    <span class="badge badge-neutral badge-sm ml-1">{{ __('auth.optional') }}</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" autocomplete="name" autofocus
                    placeholder="{{ __('auth.full_name_placeholder') }}"
                    class="input input-bordered w-full" />
                @error('name')
                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('auth.username') }}</label>
                <input type="text" name="username" value="{{ old('username') }}" autocomplete="username"
                    class="input input-bordered w-full" />
                <p class="text-xs text-base-content/40 mt-1">{{ __('auth.username_hint') }}</p>
                @error('username')
                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('auth.email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="email"
                    class="input input-bordered w-full" />
                @error('email')
                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('auth.password') }}</label>
                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" autocomplete="new-password"
                        class="input input-bordered w-full pr-10" />
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-base-content/40 hover:text-base-content/70">
                        <svg x-show="!show" x-cloak x-transition.opacity.duration.150ms class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        <svg x-show="show" x-cloak x-transition.opacity.duration.150ms class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('auth.confirm_password') }}</label>
                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" name="password_confirmation" autocomplete="new-password"
                        class="input input-bordered w-full pr-10" />
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-base-content/40 hover:text-base-content/70">
                        <svg x-show="!show" x-cloak x-transition.opacity.duration.150ms class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        <svg x-show="show" x-cloak x-transition.opacity.duration.150ms class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-neutral w-full active:scale-95">{{ __('auth.create_account_button') }}</button>
        </div>
    </form>

    <div class="divider my-6"></div>

    <p class="text-sm text-center text-base-content/60">
        {{ __('auth.have_account') }}
        <a href="{{ route('login') }}" class="text-base-content font-medium hover:underline">{{ __('auth.login_link') }}</a>
    </p>
@endsection
