@extends('layouts.auth')

@section('title', 'Terima Undangan')
@section('subtitle', 'Finish setting up your whitearchive.id account')

@section('content')
    @if(isset($error))
        <div class="flex items-start gap-3 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm mb-6">
            {{ $error }}
        </div>
    @elseif(isset($invitation))
        <div class="flex items-start gap-3 p-4 rounded-lg bg-blue-50 border border-blue-200 text-blue-800 text-sm mb-6">
            Undangan untuk <strong class="mx-1">{{ $invitation->email }}</strong> sebagai <strong>{{ $invitation->role->name }}</strong>.
        </div>

        <form method="POST" action="{{ route('invite.store') }}" novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ request('token') }}">

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" autofocus
                        class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent" />
                    @error('name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}"
                        class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent" />
                    @error('username')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Email</label>
                    <input type="email" value="{{ $invitation->email }}" disabled
                        class="w-full border border-zinc-200 rounded-lg px-3 py-2 text-sm text-zinc-400 bg-zinc-50 cursor-not-allowed" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Password</label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" autocomplete="new-password"
                            class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent pr-10" />
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Confirm Password</label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" autocomplete="new-password"
                            class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent pr-10" />
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-800 transition-colors">Activate Account</button>
            </div>
        </form>
    @endif
@endsection
