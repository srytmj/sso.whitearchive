@extends('layouts.dashboard')
@section('title', __('users.invite_title'))
@section('heading', __('users.invite_title'))

@section('content')
    <div class="max-w-lg">
        <div class="card bg-base-100 border border-base-300">
            <div class="card-body p-6">
                <form method="POST" action="{{ route('dashboard.users.send-invite') }}" novalidate>
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('users.email_address') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="input w-full" />
                            @error('email')
                                <p class="text-xs text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('users.role') }}</label>
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
                                        class="btn btn-outline w-full flex items-center justify-between gap-2 active:scale-95">
                                    <span x-text="current.label"></span>
                                    <svg class="w-4 h-4 text-base-content/60 shrink-0 transition-transform duration-150" :class="open ? 'rotate-180' : ''"
                                         fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                                <div x-show="open" x-cloak
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="menu absolute z-10 mt-1 w-full bg-base-100 border border-base-300 rounded-box shadow-md py-1">
                                    <template x-for="opt in options" :key="opt.value">
                                        <button type="button" @click="select(opt)"
                                                class="w-full text-left px-3 py-2 text-sm rounded-lg hover:bg-base-200 transition-colors"
                                                :class="value === opt.value ? 'text-base-content font-medium bg-base-200' : 'text-base-content/70'">
                                            <span x-text="opt.label"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            @error('role_id')
                                <p class="text-xs text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <p class="text-xs text-base-content/60">{{ __('users.invite_link_note') }}</p>

                        <div class="flex gap-3">
                            <button type="submit" class="btn btn-neutral active:scale-95">{{ __('users.send_invite_button') }}</button>
                            <a href="{{ route('dashboard.users.index') }}" class="btn btn-ghost">{{ __('users.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
