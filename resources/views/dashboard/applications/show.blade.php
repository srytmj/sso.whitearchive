@extends('layouts.dashboard')
@section('title', $client->name)
@section('heading', $client->name)

@section('content')
    @if(session('new_secret'))
        {{-- Quick Start Panel — only shown once after creation --}}
        <div class="mb-6" x-data="{
            clientId: '{{ $client->id }}',
            secret: '{{ session('new_secret') }}',
            redirectUri: '{{ $client->redirect }}',
            baseUrl: '{{ config('app.url') }}',
            copied: null,
            async copy(key, text) {
                await navigator.clipboard.writeText(text);
                this.copied = key;
                setTimeout(() => this.copied = null, 2000);
            },
            get envSnippet() {
                return `SSO_CLIENT_ID=${this.clientId}\nSSO_CLIENT_SECRET=${this.secret}\nSSO_BASE_URL={{ rtrim(config('app.url'), '/') }}\nSSO_REDIRECT_URI=${this.redirectUri}`;
            },
            get authUrl() {
                return `{{ rtrim(config('app.url'), '/') }}/oauth/authorize?client_id=${this.clientId}&redirect_uri=${encodeURIComponent(this.redirectUri)}&response_type=code&scope=profile:read&code_challenge=PKCE_CHALLENGE_HERE&code_challenge_method=S256`;
            }
        }">
            <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-xl p-5 mb-4">
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-amber-200 dark:bg-amber-900/60 text-amber-800 dark:text-amber-200">{{ __('applications.save_now_warning') }}</span>
                </div>

                {{-- Client ID --}}
                <div class="mb-3">
                    <p class="text-xs font-medium text-amber-700 dark:text-amber-400 mb-1">{{ __('applications.client_id_label') }}</p>
                    <div class="flex items-center gap-2">
                        <code class="flex-1 font-mono text-sm text-zinc-800 dark:text-zinc-200 bg-white dark:bg-zinc-900 border border-amber-200 dark:border-amber-900 rounded-lg px-3 py-2 break-all">{{ $client->id }}</code>
                        <button @click="copy('id', clientId)" class="shrink-0 px-3 py-2 text-xs font-medium rounded-lg border border-amber-200 dark:border-amber-900 bg-white dark:bg-zinc-900 text-amber-700 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-950/50 transition-colors">
                            <span x-show="copied !== 'id'">{{ __('applications.copy') }}</span>
                            <span x-show="copied === 'id'" class="text-green-600 dark:text-green-400">{{ __('applications.copied') }}</span>
                        </button>
                    </div>
                </div>

                {{-- Client Secret --}}
                <div class="mb-3">
                    <p class="text-xs font-medium text-amber-700 dark:text-amber-400 mb-1">{{ __('applications.client_secret_label') }}</p>
                    <div class="flex items-center gap-2">
                        <code class="flex-1 font-mono text-sm text-zinc-800 dark:text-zinc-200 bg-white dark:bg-zinc-900 border border-amber-200 dark:border-amber-900 rounded-lg px-3 py-2 break-all">{{ session('new_secret') }}</code>
                        <button @click="copy('secret', secret)" class="shrink-0 px-3 py-2 text-xs font-medium rounded-lg border border-amber-200 dark:border-amber-900 bg-white dark:bg-zinc-900 text-amber-700 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-950/50 transition-colors">
                            <span x-show="copied !== 'secret'">{{ __('applications.copy') }}</span>
                            <span x-show="copied === 'secret'" class="text-green-600 dark:text-green-400">{{ __('applications.copied') }}</span>
                        </button>
                    </div>
                </div>

                {{-- .env snippet --}}
                <div class="mb-3">
                    <p class="text-xs font-medium text-amber-700 dark:text-amber-400 mb-1">{{ __('applications.env_snippet_label') }}</p>
                    <div class="flex items-start gap-2">
                        <pre class="flex-1 font-mono text-xs text-zinc-800 dark:text-zinc-200 bg-white dark:bg-zinc-900 border border-amber-200 dark:border-amber-900 rounded-lg px-3 py-2 overflow-x-auto whitespace-pre">SSO_CLIENT_ID={{ $client->id }}
SSO_CLIENT_SECRET={{ session('new_secret') }}
SSO_BASE_URL={{ rtrim(config('app.url'), '/') }}
SSO_REDIRECT_URI={{ $client->redirect }}</pre>
                        <button @click="copy('env', envSnippet)" class="shrink-0 px-3 py-2 text-xs font-medium rounded-lg border border-amber-200 dark:border-amber-900 bg-white dark:bg-zinc-900 text-amber-700 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-950/50 transition-colors">
                            <span x-show="copied !== 'env'">{{ __('applications.copy') }}</span>
                            <span x-show="copied === 'env'" class="text-green-600 dark:text-green-400">{{ __('applications.copied') }}</span>
                        </button>
                    </div>
                </div>

                {{-- Authorization URL --}}
                <div>
                    <p class="text-xs font-medium text-amber-700 dark:text-amber-400 mb-1">{{ __('applications.auth_url_label') }}</p>
                    <div class="flex items-start gap-2">
                        <code x-text="authUrl" class="flex-1 font-mono text-xs text-zinc-800 dark:text-zinc-200 bg-white dark:bg-zinc-900 border border-amber-200 dark:border-amber-900 rounded-lg px-3 py-2 break-all block"></code>
                        <button @click="copy('url', authUrl)" class="shrink-0 px-3 py-2 text-xs font-medium rounded-lg border border-amber-200 dark:border-amber-900 bg-white dark:bg-zinc-900 text-amber-700 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-950/50 transition-colors">
                            <span x-show="copied !== 'url'">{{ __('applications.copy') }}</span>
                            <span x-show="copied === 'url'" class="text-green-600 dark:text-green-400">{{ __('applications.copied') }}</span>
                        </button>
                    </div>
                    <p class="text-xs text-amber-600 dark:text-amber-500 mt-1.5">{!! __('applications.auth_url_hint', ['placeholder' => '<code class="bg-amber-100 dark:bg-amber-900/60 px-1 rounded">PKCE_CHALLENGE_HERE</code>']) !!}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-lg space-y-6">
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('applications.credentials_heading') }}</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-1">{{ __('applications.client_id_label') }}</p>
                    <code class="font-mono text-sm text-zinc-800 dark:text-zinc-200">{{ $client->id }}</code>
                </div>
                <div>
                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-1">{{ __('applications.client_secret_label') }}</p>
                    <p class="text-sm text-zinc-400 dark:text-zinc-500 italic">{{ __('applications.secret_hidden') }}</p>
                </div>
                <div>
                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-1">{{ __('applications.redirect_uri_label') }}</p>
                    <code class="font-mono text-sm text-zinc-800 dark:text-zinc-200 break-all">{{ $client->redirect }}</code>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('applications.edit_heading') }}</h2>
            <form method="POST" action="{{ route('dashboard.applications.update', $client->id) }}" novalidate>
                @csrf @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('applications.name_label') }}</label>
                        <input type="text" name="name" value="{{ old('name', $client->name) }}"
                            class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                        @error('name')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('applications.redirect_uri_label') }}</label>
                        <input type="url" name="redirect_uri" value="{{ old('redirect_uri', $client->redirect) }}"
                            class="w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-800 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent" />
                        @error('redirect_uri')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">{{ __('applications.save_changes_button') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
