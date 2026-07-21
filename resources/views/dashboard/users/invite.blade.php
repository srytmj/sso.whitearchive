@extends('layouts.dashboard')
@section('title', 'Invite User')
@section('heading', 'Invite User')

@section('content')
    <div class="max-w-lg">
        <div class="bg-white border border-zinc-200 rounded-xl p-6">
            <form method="POST" action="{{ route('dashboard.users.send-invite') }}" novalidate>
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent" />
                        @error('email')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">Role</label>
                        <select name="role_id"
                            class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <p class="text-xs text-zinc-400">Link undangan berlaku selama 24 jam dan hanya bisa digunakan sekali.</p>

                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-800 transition-colors">Send Invite</button>
                        <a href="{{ route('dashboard.users.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-zinc-600 text-sm font-medium rounded-lg hover:bg-zinc-100 transition-colors">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
