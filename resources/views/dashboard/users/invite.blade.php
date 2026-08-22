@extends('layouts.dashboard')
@section('title', __('users.invite_title'))
@section('heading', __('users.invite_title'))

@section('content')
    <div class="max-w-lg">
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
            <form method="POST" action="{{ route('dashboard.users.send-invite') }}" novalidate>
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('users.email_address') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                        @error('email')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('users.role') }}</label>
                        <div x-data="{
                                open: false,
                                value: {{ old('role_id', $roles->first()->id ?? 0) }},
                                options: [
                                    @foreach($roles as $role)
                                        { value: {{ $role->id }}, label: @js($role->name) },
                                    @endforeach
                                ],
                                get current() { return this.options.find(o => o.value === this.value) ?? this.options[0]; },
                                select(opt) { this.open = false; this.value = opt.value; }
                             }"
                             @click.outside="open = false"
                             class="relative">
                            <input type="hidden" name="role_id" :value="value">
                            <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 hover:border-zinc-400 dark:hover:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 dark:focus:ring-zinc-100/10 transition-shadow">
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
                                            class="w-full text-left px-3 py-2 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors"
                                            :class="value === opt.value ? 'text-zinc-900 dark:text-zinc-100 font-medium bg-zinc-50 dark:bg-zinc-700' : 'text-zinc-600 dark:text-zinc-300'">
                                        <span x-text="opt.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        @error('role_id')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <p class="text-xs text-zinc-400 dark:text-zinc-500">{{ __('users.invite_link_note') }}</p>

                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">{{ __('users.send_invite_button') }}</button>
                        <a href="{{ route('dashboard.users.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-zinc-600 dark:text-zinc-400 text-sm font-medium rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">{{ __('users.cancel') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
