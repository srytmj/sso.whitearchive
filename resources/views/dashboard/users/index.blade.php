@extends('layouts.dashboard')
@section('title', 'Users')
@section('heading', 'Users')

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('dashboard.users.invite') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-800 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" /></svg>
            Invite User
        </a>
    </div>

    <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 border-b border-zinc-100">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500">User</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500 hidden sm:table-cell">Role</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500 hidden md:table-cell">Joined</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @forelse($users as $user)
                    <tr>
                        <td class="px-5 py-3">
                            <p class="text-sm font-medium text-zinc-900">{{ $user->name }}</p>
                            <p class="text-xs text-zinc-400">{{ $user->email }}</p>
                        </td>
                        <td class="px-5 py-3 hidden sm:table-cell">
                            <form method="POST" action="{{ route('dashboard.users.role', $user->id) }}">
                                @csrf @method('PATCH')
                                <select name="role_id" onchange="this.form.submit()"
                                    class="border border-zinc-300 rounded-lg px-2 py-1 text-xs text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->role_id === $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-5 py-3">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-700">Active</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-red-100 text-red-700">Inactive</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-zinc-400 text-xs hidden md:table-cell">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <form method="POST" action="{{ route('dashboard.users.toggle-active', $user->id) }}">
                                @csrf @method('PATCH')
                                @if($user->is_active)
                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition-colors">Deactivate</button>
                                @else
                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 text-zinc-600 text-xs font-medium rounded-lg hover:bg-zinc-100 transition-colors">Activate</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center">
                            <p class="text-sm text-zinc-400">No users found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t border-zinc-50">{{ $users->links() }}</div>
    </div>
@endsection
