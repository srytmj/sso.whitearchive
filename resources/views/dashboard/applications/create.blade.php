@extends('layouts.dashboard')
@section('title', __('applications.create_title'))
@section('heading', __('applications.create_title'))

@section('content')
    <div class="max-w-lg">
        <div class="card bg-base-100 border border-base-300">
            <div class="card-body p-6">
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
                            <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('applications.app_name_label') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="input w-full" />
                            @error('name')
                                <p class="text-xs text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('applications.redirect_uri_label') }}</label>
                            <input type="url" name="redirect_uri"
                                x-model="redirectUri"
                                @input="validateUri($event.target.value)"
                                placeholder="{{ __('applications.redirect_uri_placeholder') }}"
                                :class="uriStatus === 'error' ? 'input-error' : uriStatus === 'warning' ? 'input-warning' : uriStatus === 'valid' ? 'input-success' : ''"
                                class="input w-full" />
                            <p x-show="uriStatus === 'error'" x-text="uriMessage" x-transition class="text-xs text-error mt-1"></p>
                            <p x-show="uriStatus === 'warning'" x-text="uriMessage" x-transition class="text-xs text-warning mt-1"></p>
                            <p x-show="uriStatus === 'valid'" x-text="uriMessage" x-transition class="text-xs text-success mt-1"></p>
                            @error('redirect_uri')
                                <p class="text-xs text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit"
                                :disabled="redirectUri && !canSubmit"
                                class="btn btn-neutral active:scale-95">
                                {{ __('applications.create_button') }}
                            </button>
                            <a href="{{ route('dashboard.applications.index') }}" class="btn btn-ghost">{{ __('applications.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
