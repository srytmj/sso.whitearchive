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
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-4">
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-amber-200 text-amber-800">Simpan sekarang — tidak bisa dilihat lagi</span>
                </div>

                {{-- Client ID --}}
                <div class="mb-3">
                    <p class="text-xs font-medium text-amber-700 mb-1">Client ID</p>
                    <div class="flex items-center gap-2">
                        <code class="flex-1 font-mono text-sm text-zinc-800 bg-white border border-amber-200 rounded-lg px-3 py-2 break-all">{{ $client->id }}</code>
                        <button @click="copy('id', clientId)" class="shrink-0 px-3 py-2 text-xs font-medium rounded-lg border border-amber-200 bg-white text-amber-700 hover:bg-amber-100 transition-colors">
                            <span x-show="copied !== 'id'">Copy</span>
                            <span x-show="copied === 'id'" class="text-green-600">Copied!</span>
                        </button>
                    </div>
                </div>

                {{-- Client Secret --}}
                <div class="mb-3">
                    <p class="text-xs font-medium text-amber-700 mb-1">Client Secret</p>
                    <div class="flex items-center gap-2">
                        <code class="flex-1 font-mono text-sm text-zinc-800 bg-white border border-amber-200 rounded-lg px-3 py-2 break-all">{{ session('new_secret') }}</code>
                        <button @click="copy('secret', secret)" class="shrink-0 px-3 py-2 text-xs font-medium rounded-lg border border-amber-200 bg-white text-amber-700 hover:bg-amber-100 transition-colors">
                            <span x-show="copied !== 'secret'">Copy</span>
                            <span x-show="copied === 'secret'" class="text-green-600">Copied!</span>
                        </button>
                    </div>
                </div>

                {{-- .env snippet --}}
                <div class="mb-3">
                    <p class="text-xs font-medium text-amber-700 mb-1">.env snippet</p>
                    <div class="flex items-start gap-2">
                        <pre class="flex-1 font-mono text-xs text-zinc-800 bg-white border border-amber-200 rounded-lg px-3 py-2 overflow-x-auto whitespace-pre">SSO_CLIENT_ID={{ $client->id }}
SSO_CLIENT_SECRET={{ session('new_secret') }}
SSO_BASE_URL={{ rtrim(config('app.url'), '/') }}
SSO_REDIRECT_URI={{ $client->redirect }}</pre>
                        <button @click="copy('env', envSnippet)" class="shrink-0 px-3 py-2 text-xs font-medium rounded-lg border border-amber-200 bg-white text-amber-700 hover:bg-amber-100 transition-colors">
                            <span x-show="copied !== 'env'">Copy</span>
                            <span x-show="copied === 'env'" class="text-green-600">Copied!</span>
                        </button>
                    </div>
                </div>

                {{-- Authorization URL --}}
                <div>
                    <p class="text-xs font-medium text-amber-700 mb-1">Contoh Authorization URL</p>
                    <div class="flex items-start gap-2">
                        <code x-text="authUrl" class="flex-1 font-mono text-xs text-zinc-800 bg-white border border-amber-200 rounded-lg px-3 py-2 break-all block"></code>
                        <button @click="copy('url', authUrl)" class="shrink-0 px-3 py-2 text-xs font-medium rounded-lg border border-amber-200 bg-white text-amber-700 hover:bg-amber-100 transition-colors">
                            <span x-show="copied !== 'url'">Copy</span>
                            <span x-show="copied === 'url'" class="text-green-600">Copied!</span>
                        </button>
                    </div>
                    <p class="text-xs text-amber-600 mt-1.5">Ganti <code class="bg-amber-100 px-1 rounded">PKCE_CHALLENGE_HERE</code> dengan code challenge yang di-generate dari PKCE flow di aplikasi kamu.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-lg space-y-6">
        <div class="bg-white border border-zinc-200 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-zinc-900 mb-4">Credentials</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-zinc-400 mb-1">Client ID</p>
                    <code class="font-mono text-sm text-zinc-800">{{ $client->id }}</code>
                </div>
                <div>
                    <p class="text-xs text-zinc-400 mb-1">Client Secret</p>
                    <p class="text-sm text-zinc-400 italic">Hidden — only shown once at creation</p>
                </div>
                <div>
                    <p class="text-xs text-zinc-400 mb-1">Redirect URI</p>
                    <code class="font-mono text-sm text-zinc-800 break-all">{{ $client->redirect }}</code>
                </div>
            </div>
        </div>

        <div class="bg-white border border-zinc-200 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-zinc-900 mb-4">Edit</h2>
            <form method="POST" action="{{ route('dashboard.applications.update', $client->id) }}" novalidate>
                @csrf @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', $client->name) }}"
                            class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent" />
                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">Redirect URI</label>
                        <input type="url" name="redirect_uri" value="{{ old('redirect_uri', $client->redirect) }}"
                            class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent" />
                        @error('redirect_uri')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-800 transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection
