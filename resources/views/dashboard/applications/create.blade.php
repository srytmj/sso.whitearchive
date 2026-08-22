@extends('layouts.dashboard')
@section('title', __('applications.create_title'))
@section('heading', __('applications.create_title'))

@section('content')
    <div class="max-w-lg">
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
            <form method="POST" action="{{ route('dashboard.applications.store') }}" novalidate
                x-data="{
                    redirectUri: '{{ old('redirect_uri') }}',
                    uriStatus: null,
                    uriMessage: '',
                    debounceTimer: null,
                    messages: {
                        invalid: @js(__('applications.validate_invalid_format')),
                        fragment: @js(__('applications.validate_no_fragment')),
                        scheme: @js(__('applications.validate_scheme_forbidden')),
                        localhost: @js(__('applications.validate_localhost_warning')),
                        https: @js(__('applications.validate_https_required')),
                        valid: @js(__('applications.validate_valid')),
                    },
                    validateUri(value) {
                        clearTimeout(this.debounceTimer);
                        if (!value) { this.uriStatus = null; this.uriMessage = ''; return; }
                        this.debounceTimer = setTimeout(() => {
                            let parsed;
                            try { parsed = new URL(value); } catch {
                                this.uriStatus = 'error';
                                this.uriMessage = this.messages.invalid;
                                return;
                            }
                            const scheme = parsed.protocol.replace(':', '').toLowerCase();
                            const host = parsed.hostname.toLowerCase();
                            if (value.includes('#')) {
                                this.uriStatus = 'error';
                                this.uriMessage = this.messages.fragment;
                                return;
                            }
                            if (['javascript', 'data', 'file'].includes(scheme)) {
                                this.uriStatus = 'error';
                                this.uriMessage = this.messages.scheme;
                                return;
                            }
                            const isLocalhost = host === 'localhost' || host === '127.0.0.1';
                            if (isLocalhost) {
                                this.uriStatus = 'warning';
                                this.uriMessage = this.messages.localhost;
                                return;
                            }
                            if (scheme === 'http') {
                                this.uriStatus = 'error';
                                this.uriMessage = this.messages.https;
                                return;
                            }
                            if (scheme !== 'https') {
                                this.uriStatus = 'error';
                                this.uriMessage = this.messages.invalid;
                                return;
                            }
                            this.uriStatus = 'valid';
                            this.uriMessage = this.messages.valid;
                        }, 500);
                    },
                    get canSubmit() {
                        return this.uriStatus === 'valid' || this.uriStatus === 'warning';
                    }
                }">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('applications.app_name_label') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                        @error('name')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('applications.redirect_uri_label') }}</label>
                        <input type="url" name="redirect_uri"
                            x-model="redirectUri"
                            @input="validateUri($event.target.value)"
                            placeholder="{{ __('applications.redirect_uri_placeholder') }}"
                            :class="uriStatus === 'error' ? 'border-red-400 focus:ring-red-500' : uriStatus === 'warning' ? 'border-amber-400 focus:ring-amber-500' : uriStatus === 'valid' ? 'border-green-400 focus:ring-green-500' : 'border-zinc-300 dark:border-zinc-700 focus:ring-zinc-900 dark:focus:ring-zinc-100'"
                            class="w-full border rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:border-transparent" />
                        <p x-show="uriStatus === 'error'" x-text="uriMessage" class="text-xs text-red-600 dark:text-red-400 mt-1"></p>
                        <p x-show="uriStatus === 'warning'" x-text="uriMessage" class="text-xs text-amber-600 dark:text-amber-400 mt-1"></p>
                        <p x-show="uriStatus === 'valid'" x-text="uriMessage" class="text-xs text-green-600 dark:text-green-400 mt-1"></p>
                        @error('redirect_uri')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            :disabled="redirectUri && !canSubmit"
                            :class="redirectUri && !canSubmit ? 'opacity-50 cursor-not-allowed' : 'hover:bg-zinc-800 dark:hover:bg-white'"
                            class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg transition-colors">
                            {{ __('applications.create_button') }}
                        </button>
                        <a href="{{ route('dashboard.applications.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-zinc-600 dark:text-zinc-400 text-sm font-medium rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">{{ __('applications.cancel') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
