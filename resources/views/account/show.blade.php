@extends('layouts.account')

@section('title', __('account.title'))

@section('content')
    {{-- Profile Info --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6 mb-6">
        <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('account.profile_heading') }}</h2>

        <div class="flex items-center gap-4 mb-6">
            @if($user->avatar)
                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-full object-cover flex-shrink-0">
            @else
                <div class="w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-300 font-semibold text-lg flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div class="min-w-0">
                <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $user->name }}</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-2">{{ '@' . $user->username }}</p>
                <form method="POST" action="{{ route('account.avatar') }}" enctype="multipart/form-data" class="flex items-center gap-2"
                      x-data="{ fileName: '' }">
                    @csrf
                    <label class="cursor-pointer text-xs text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 underline underline-offset-2">
                        <span x-text="fileName || @js(__('account.change_avatar'))"></span>
                        <input type="file" name="avatar" accept="image/png,image/jpeg,image/webp" class="hidden"
                               @change="fileName = $event.target.files[0]?.name; $el.closest('form').querySelector('button').classList.remove('hidden')">
                    </label>
                    <button type="submit" class="hidden text-xs px-2 py-1 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 rounded-md hover:bg-zinc-800 dark:hover:bg-white transition-colors">{{ __('account.upload_button') }}</button>
                </form>
                @error('avatar')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-1">{{ __('account.email') }}</p>
                <p class="text-sm text-zinc-900 dark:text-zinc-100">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-1">{{ __('account.role') }}</p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">{{ $user->role->name ?? '—' }}</span>
            </div>
            <div>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-1">{{ __('account.email_verification') }}</p>
                @if($user->email_verified_at)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300">{{ __('account.verified') }}</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300">{{ __('account.not_verified') }}</span>
                @endif
            </div>
            <div>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-1">{{ __('account.account_status') }}</p>
                @if($user->is_active)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300">{{ __('account.active') }}</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300">{{ __('account.inactive') }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Preferences --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6 mb-6">
        <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-1">{{ __('account.preferences_heading') }}</h2>
        <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-4">{{ __('account.language_help') }}</p>

        <form method="POST" action="{{ route('account.locale') }}">
            @csrf
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('account.language_label') }}</label>
            <div x-data="{
                    open: false,
                    value: @js($user->locale),
                    options: [
                        { value: 'id', label: 'Bahasa Indonesia' },
                        { value: 'en', label: 'English' },
                        { value: 'ja', label: '日本語' },
                    ],
                    get current() { return this.options.find(o => o.value === this.value); },
                    select(opt) {
                        this.open = false;
                        if (opt.value === this.value) return;
                        this.value = opt.value;
                        $el.closest('form').submit();
                    }
                 }"
                 @click.outside="open = false"
                 class="relative w-full sm:w-64">
                <input type="hidden" name="locale" :value="value">
                <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 hover:border-zinc-300 dark:hover:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 dark:focus:ring-zinc-100/10 transition-shadow">
                    <span x-text="current.label"></span>
                    <svg class="w-4 h-4 text-zinc-400 shrink-0 transition-transform duration-150" :class="open ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div x-show="open" x-transition x-cloak
                     class="absolute z-10 mt-1 w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg py-1">
                    <template x-for="opt in options" :key="opt.value">
                        <button type="button" @click="select(opt)"
                                class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors"
                                :class="value === opt.value ? 'text-zinc-900 dark:text-zinc-100 font-medium bg-zinc-50 dark:bg-zinc-700' : 'text-zinc-600 dark:text-zinc-300'">
                            <span x-text="opt.label"></span>
                            <svg x-show="value === opt.value" class="w-3.5 h-3.5 text-zinc-900 dark:text-zinc-100 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </button>
                    </template>
                </div>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
        <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('account.change_password_heading') }}</h2>

        <form method="POST" action="{{ route('account.password') }}" novalidate>
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('account.current_password') }}</label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="current_password" autocomplete="current-password"
                            class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent pr-10" />
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                            <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('account.new_password') }}</label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="new_password" autocomplete="new-password"
                            class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent pr-10" />
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                            <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                    @error('new_password')
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('account.confirm_new_password') }}</label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="new_password_confirmation" autocomplete="new-password"
                            class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent pr-10" />
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                            <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">{{ __('account.update_password_button') }}</button>
            </div>
        </form>
    </div>
@endsection
