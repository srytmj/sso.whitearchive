@extends('layouts.dashboard')
@section('title', __('applications.title'))
@section('heading', __('applications.title'))

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('dashboard.applications.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            {{ __('applications.add_button') }}
        </a>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 dark:bg-zinc-800/60 border-b border-zinc-100 dark:border-zinc-800">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ __('applications.th_name') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400 hidden sm:table-cell">{{ __('applications.th_client_id') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400 hidden md:table-cell">{{ __('applications.th_created') }}</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800">
                @forelse($clients as $client)
                    <tr>
                        <td class="px-5 py-3 font-medium text-zinc-900 dark:text-zinc-100">{{ $client->name }}</td>
                        <td class="px-5 py-3 text-zinc-500 dark:text-zinc-400 font-mono text-xs hidden sm:table-cell">{{ $client->id }}</td>
                        <td class="px-5 py-3 text-zinc-400 dark:text-zinc-500 text-xs hidden md:table-cell">{{ $client->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('dashboard.applications.show', $client->id) }}" class="text-xs text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100">{{ __('applications.detail') }}</a>
                                <form method="POST" action="{{ route('dashboard.applications.destroy', $client->id) }}"
                                    onsubmit="return confirm('{{ __('applications.delete_confirm') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition-colors">{{ __('applications.delete') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center">
                            <p class="text-sm text-zinc-400 dark:text-zinc-500">{{ __('applications.no_applications') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
