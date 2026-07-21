@extends('layouts.dashboard')
@section('title', 'Applications')
@section('heading', 'Applications')

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('dashboard.applications.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-800 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Add Application
        </a>
    </div>

    <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 border-b border-zinc-100">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500">Name</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500 hidden sm:table-cell">Client ID</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500 hidden md:table-cell">Created</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @forelse($clients as $client)
                    <tr>
                        <td class="px-5 py-3 font-medium text-zinc-900">{{ $client->name }}</td>
                        <td class="px-5 py-3 text-zinc-500 font-mono text-xs hidden sm:table-cell">{{ $client->id }}</td>
                        <td class="px-5 py-3 text-zinc-400 text-xs hidden md:table-cell">{{ $client->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('dashboard.applications.show', $client->id) }}" class="text-xs text-zinc-600 hover:text-zinc-900">Detail</a>
                                <form method="POST" action="{{ route('dashboard.applications.destroy', $client->id) }}"
                                    onsubmit="return confirm('Hapus client dan revoke semua token?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition-colors">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center">
                            <p class="text-sm text-zinc-400">No applications registered.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
