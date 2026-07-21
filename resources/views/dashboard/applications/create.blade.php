@extends('layouts.dashboard')
@section('title', 'Add Application')
@section('heading', 'Add Application')

@section('content')
    <div class="max-w-lg">
        <div class="bg-white border border-zinc-200 rounded-xl p-6">
            <form method="POST" action="{{ route('dashboard.applications.store') }}" novalidate
                x-data="{
                    redirectUri: '{{ old('redirect_uri') }}',
                    uriStatus: null,
                    uriMessage: '',
                    debounceTimer: null,
                    validateUri(value) {
                        clearTimeout(this.debounceTimer);
                        if (!value) { this.uriStatus = null; this.uriMessage = ''; return; }
                        this.debounceTimer = setTimeout(() => {
                            let parsed;
                            try { parsed = new URL(value); } catch {
                                this.uriStatus = 'error';
                                this.uriMessage = 'Format URI tidak valid';
                                return;
                            }
                            const scheme = parsed.protocol.replace(':', '').toLowerCase();
                            const host = parsed.hostname.toLowerCase();
                            if (value.includes('#')) {
                                this.uriStatus = 'error';
                                this.uriMessage = 'Redirect URI tidak boleh mengandung fragment (#)';
                                return;
                            }
                            if (['javascript', 'data', 'file'].includes(scheme)) {
                                this.uriStatus = 'error';
                                this.uriMessage = 'Scheme tidak diizinkan';
                                return;
                            }
                            const isLocalhost = host === 'localhost' || host === '127.0.0.1';
                            if (isLocalhost) {
                                this.uriStatus = 'warning';
                                this.uriMessage = 'URI development — pastikan sudah diganti saat production';
                                return;
                            }
                            if (scheme === 'http') {
                                this.uriStatus = 'error';
                                this.uriMessage = 'Wajib menggunakan HTTPS untuk domain publik';
                                return;
                            }
                            if (scheme !== 'https') {
                                this.uriStatus = 'error';
                                this.uriMessage = 'Format URI tidak valid';
                                return;
                            }
                            this.uriStatus = 'valid';
                            this.uriMessage = 'URI valid';
                        }, 500);
                    },
                    get canSubmit() {
                        return this.uriStatus === 'valid' || this.uriStatus === 'warning';
                    }
                }">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">Application Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent" />
                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">Redirect URI</label>
                        <input type="url" name="redirect_uri"
                            x-model="redirectUri"
                            @input="validateUri($event.target.value)"
                            placeholder="https://app.example.com/auth/callback"
                            :class="uriStatus === 'error' ? 'border-red-400 focus:ring-red-500' : uriStatus === 'warning' ? 'border-amber-400 focus:ring-amber-500' : uriStatus === 'valid' ? 'border-green-400 focus:ring-green-500' : 'border-zinc-300 focus:ring-zinc-900'"
                            class="w-full border rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:border-transparent" />
                        <p x-show="uriStatus === 'error'" x-text="uriMessage" class="text-xs text-red-600 mt-1"></p>
                        <p x-show="uriStatus === 'warning'" x-text="uriMessage" class="text-xs text-amber-600 mt-1"></p>
                        <p x-show="uriStatus === 'valid'" x-text="uriMessage" class="text-xs text-green-600 mt-1"></p>
                        @error('redirect_uri')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            :disabled="redirectUri && !canSubmit"
                            :class="redirectUri && !canSubmit ? 'opacity-50 cursor-not-allowed' : 'hover:bg-zinc-800'"
                            class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 text-white text-sm font-medium rounded-lg transition-colors">
                            Create Application
                        </button>
                        <a href="{{ route('dashboard.applications.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-zinc-600 text-sm font-medium rounded-lg hover:bg-zinc-100 transition-colors">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
