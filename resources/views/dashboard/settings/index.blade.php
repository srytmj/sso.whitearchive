@extends('layouts.dashboard')
@section('title', __('settings.title'))
@section('heading', __('settings.title'))

@section('content')
    <div class="max-w-2xl space-y-6">
        {{-- Mail Settings --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('settings.mail_heading') }}</h2>
            <form method="POST" action="{{ route('dashboard.settings.mail') }}"
                  x-data="{ driver: @js($settings['mail_driver'] ?? 'resend') }">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.mail_driver_label') }}</label>
                        <div x-data="{
                                open: false,
                                options: [
                                    { value: 'resend', label: 'Resend' },
                                    { value: 'smtp', label: 'SMTP' },
                                ],
                                get current() { return this.options.find(o => o.value === driver); },
                                select(opt) { this.open = false; driver = opt.value; }
                             }"
                             @click.outside="open = false"
                             class="relative w-48">
                            <input type="hidden" name="mail_driver" :value="driver">
                            <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 hover:border-zinc-400 dark:hover:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 dark:focus:ring-zinc-100/10 transition-shadow">
                                <span x-text="current.label"></span>
                                <svg class="w-4 h-4 text-zinc-400 dark:text-zinc-500 shrink-0 transition-transform duration-150" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition x-cloak class="absolute z-10 mt-1 w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg py-1">
                                <template x-for="opt in options" :key="opt.value">
                                    <button type="button" @click="select(opt)" class="w-full text-left px-3 py-2 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors" :class="driver === opt.value ? 'text-zinc-900 dark:text-zinc-100 font-medium bg-zinc-50 dark:bg-zinc-700' : 'text-zinc-600 dark:text-zinc-300'">
                                        <span x-text="opt.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.mail_from_address_label') }}</label>
                            <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}"
                                class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.mail_from_name_label') }}</label>
                            <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}"
                                class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                        </div>
                    </div>

                    <div x-show="driver === 'resend'" x-cloak>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.resend_api_key_label') }}</label>
                        <input type="password" name="resend_api_key" placeholder="{{ __('settings.leave_blank_unchanged') }}"
                            class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                    </div>

                    <div x-show="driver === 'smtp'" x-cloak class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.smtp_host_label') }}</label>
                                <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.smtp_port_label') }}</label>
                                <input type="number" name="smtp_port" value="{{ old('smtp_port', $settings['smtp_port'] ?? '') }}"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.smtp_username_label') }}</label>
                                <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.smtp_password_label') }}</label>
                                <input type="password" name="smtp_password" placeholder="{{ __('settings.leave_blank_unchanged') }}"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.smtp_encryption_label') }}</label>
                            <select name="smtp_encryption" class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent">
                                <option value="tls" {{ ($settings['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">{{ __('settings.save_button') }}</button>
                </div>
            </form>
        </div>

        {{-- Test Email --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('settings.test_email_heading') }}</h2>
            <form method="POST" action="{{ route('dashboard.settings.test-email') }}" class="flex gap-3">
                @csrf
                <input type="email" name="test_email" placeholder="{{ __('settings.test_email_placeholder') }}" required
                    class="flex-1 border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors shrink-0">{{ __('settings.test_email_button') }}</button>
            </form>
        </div>

        {{-- Avatar Storage --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('settings.avatar_storage_heading') }}</h2>
            <form method="POST" action="{{ route('dashboard.settings.avatar-storage') }}"
                  x-data="{ disk: @js($settings['avatar_disk'] ?? 'local') }">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.avatar_disk_label') }}</label>
                        <div x-data="{
                                open: false,
                                options: [
                                    { value: 'local', label: @js(__('settings.avatar_disk_local')) },
                                    { value: 's3', label: @js(__('settings.avatar_disk_s3')) },
                                ],
                                get current() { return this.options.find(o => o.value === disk); },
                                select(opt) { this.open = false; disk = opt.value; }
                             }"
                             @click.outside="open = false"
                             class="relative w-48">
                            <input type="hidden" name="avatar_disk" :value="disk">
                            <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 hover:border-zinc-400 dark:hover:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 dark:focus:ring-zinc-100/10 transition-shadow">
                                <span x-text="current.label"></span>
                                <svg class="w-4 h-4 text-zinc-400 dark:text-zinc-500 shrink-0 transition-transform duration-150" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition x-cloak class="absolute z-10 mt-1 w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg py-1">
                                <template x-for="opt in options" :key="opt.value">
                                    <button type="button" @click="select(opt)" class="w-full text-left px-3 py-2 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors" :class="disk === opt.value ? 'text-zinc-900 dark:text-zinc-100 font-medium bg-zinc-50 dark:bg-zinc-700' : 'text-zinc-600 dark:text-zinc-300'">
                                        <span x-text="opt.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div x-show="disk === 's3'" x-cloak class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.s3_key_label') }}</label>
                                <input type="text" name="s3_key" value="{{ old('s3_key', $settings['s3_key'] ?? '') }}"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.s3_secret_label') }}</label>
                                <input type="password" name="s3_secret" placeholder="{{ __('settings.leave_blank_unchanged') }}"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.s3_region_label') }}</label>
                                <input type="text" name="s3_region" value="{{ old('s3_region', $settings['s3_region'] ?? '') }}"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.s3_bucket_label') }}</label>
                                <input type="text" name="s3_bucket" value="{{ old('s3_bucket', $settings['s3_bucket'] ?? '') }}"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('settings.s3_endpoint_label') }}</label>
                            <input type="text" name="s3_endpoint" value="{{ old('s3_endpoint', $settings['s3_endpoint'] ?? '') }}"
                                class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                        </div>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">{{ __('settings.save_button') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
