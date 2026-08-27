@extends('layouts.dashboard')
@section('title', __('applications.title'))
@section('heading', __('applications.title'))

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('dashboard.applications.create') }}" class="btn btn-neutral btn-sm active:scale-95">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            {{ __('applications.add_button') }}
        </a>
    </div>

    <div class="card bg-base-100 border border-base-300">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>{{ __('applications.th_name') }}</th>
                        <th class="hidden sm:table-cell">{{ __('applications.th_client_id') }}</th>
                        <th class="hidden md:table-cell">{{ __('applications.th_created') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td class="font-medium text-base-content">{{ $client->name }}</td>
                            <td class="text-base-content/60 font-mono text-xs hidden sm:table-cell">{{ $client->id }}</td>
                            <td class="text-base-content/60 text-xs hidden md:table-cell">{{ $client->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('dashboard.applications.show', $client->id) }}" class="text-xs text-base-content/60 hover:text-base-content">{{ __('applications.detail') }}</a>
                                    <form method="POST" action="{{ route('dashboard.applications.destroy', $client->id) }}"
                                        onsubmit="return confirm('{{ __('applications.delete_confirm') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-error btn-xs active:scale-95">{{ __('applications.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8">
                                <p class="text-sm text-base-content/60">{{ __('applications.no_applications') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
