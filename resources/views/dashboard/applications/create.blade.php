@extends('layouts.dashboard')
@section('title', 'Add Application')
@section('heading', 'Add Application')

@section('content')
    <div class="max-w-lg">
        <div class="bg-white border border-zinc-200 rounded-xl p-6">
            <form method="POST" action="{{ route('dashboard.applications.store') }}" novalidate>
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
                        <input type="url" name="redirect_uri" value="{{ old('redirect_uri') }}"
                            placeholder="https://app.example.com/auth/callback"
                            class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent" />
                        @error('redirect_uri')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-800 transition-colors">Create Application</button>
                        <a href="{{ route('dashboard.applications.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-zinc-600 text-sm font-medium rounded-lg hover:bg-zinc-100 transition-colors">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
