@extends('layouts.account')

@section('title', 'My Account')

@section('content')
    {{-- Profile Info --}}
    <div class="bg-white border border-zinc-200 rounded-xl p-6 mb-6">
        <h2 class="text-sm font-semibold text-zinc-900 mb-4">Profile</h2>

        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold text-lg flex-shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-zinc-900">{{ $user->name }}</p>
                <p class="text-sm text-zinc-500">{{ '@' . $user->username }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-zinc-400 mb-1">Email</p>
                <p class="text-sm text-zinc-900">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-xs text-zinc-400 mb-1">Role</p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-zinc-100 text-zinc-600">{{ $user->role->name ?? '—' }}</span>
            </div>
            <div>
                <p class="text-xs text-zinc-400 mb-1">Email Verification</p>
                @if($user->email_verified_at)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-700">Verified</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-yellow-100 text-yellow-700">Not verified</span>
                @endif
            </div>
            <div>
                <p class="text-xs text-zinc-400 mb-1">Account Status</p>
                @if($user->is_active)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-700">Active</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-red-100 text-red-700">Inactive</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="bg-white border border-zinc-200 rounded-xl p-6">
        <h2 class="text-sm font-semibold text-zinc-900 mb-4">Change Password</h2>

        <form method="POST" action="{{ route('account.password') }}" novalidate>
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Current Password</label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="current_password" autocomplete="current-password"
                            class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent pr-10" />
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">New Password</label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="new_password" autocomplete="new-password"
                            class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent pr-10" />
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                    @error('new_password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Confirm New Password</label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="new_password_confirmation" autocomplete="new-password"
                            class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent pr-10" />
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-800 transition-colors">Update Password</button>
            </div>
        </form>
    </div>
@endsection
