@extends('layouts.dashboard')
@section('title', $client->name)
@section('heading', $client->name)

@section('content')
    @if(session('new_secret'))
        <div class="flex items-start gap-3 p-4 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm mb-6">
            <div>
                <p class="font-semibold mb-0.5">Client Secret — Simpan sekarang</p>
                <p>Tidak akan ditampilkan lagi setelah halaman ini ditutup.</p>
                <code class="block mt-2 bg-white border border-yellow-200 rounded-lg px-3 py-2 text-sm font-mono text-zinc-800 break-all">{{ session('new_secret') }}</code>
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
