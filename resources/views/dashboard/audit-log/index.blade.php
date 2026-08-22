@extends('layouts.dashboard')
@section('title', __('audit.title'))
@section('heading', __('audit.title'))

@section('content')
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl mb-6 p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <form method="GET" class="flex-1">
                <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('audit.search_placeholder') }}"
                       class="w-full px-3 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 dark:focus:ring-zinc-100/10 focus:border-zinc-300 dark:focus:border-zinc-600 transition-shadow">
                @if($event)
                    <input type="hidden" name="event" value="{{ $event }}">
                @endif
            </form>

            <div x-data="{
                    open: false,
                    value: @js($event ?? ''),
                    options: [
                        { value: '', label: @js(__('audit.all_events')) },
                        @foreach($events as $ev)
                            { value: @js($ev), label: @js($ev) },
                        @endforeach
                    ],
                    get current() { return this.options.find(o => o.value === this.value) ?? this.options[0]; },
                    select(opt) {
                        this.open = false;
                        if (opt.value === this.value) return;
                        this.value = opt.value;
                        const url = new URL(window.location.href);
                        if (opt.value) { url.searchParams.set('event', opt.value); } else { url.searchParams.delete('event'); }
                        url.searchParams.delete('page');
                        window.location.href = url.toString();
                    }
                 }"
                 @click.outside="open = false"
                 class="relative w-full sm:w-64 shrink-0">
                <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 hover:border-zinc-300 dark:hover:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 dark:focus:ring-zinc-100/10 transition-shadow">
                    <span x-text="current.label" class="truncate"></span>
                    <svg class="w-4 h-4 text-zinc-400 dark:text-zinc-500 shrink-0 transition-transform duration-150" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div x-show="open" x-transition x-cloak
                     class="absolute z-10 mt-1 w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg py-1 max-h-64 overflow-y-auto">
                    <template x-for="opt in options" :key="opt.value">
                        <button type="button" @click="select(opt)"
                                class="w-full text-left px-3 py-2 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors"
                                :class="value === opt.value ? 'text-zinc-900 dark:text-zinc-100 font-medium bg-zinc-50 dark:bg-zinc-700' : 'text-zinc-600 dark:text-zinc-300'">
                            <span x-text="opt.label"></span>
                        </button>
                    </template>
                </div>
            </div>

            @if($event || $search)
                <a href="{{ route('dashboard.audit-log.index') }}" class="px-3 py-2 text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors shrink-0 self-center">
                    {{ __('common.reset') }}
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-100 dark:border-zinc-800">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('audit.entries_found', ['count' => $entries->total()]) }}</p>
        </div>

        @if($entries->isEmpty())
            <div class="px-6 py-16 text-center">
                <p class="text-sm text-zinc-400 dark:text-zinc-500">{{ __('audit.no_match') }}</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/60 border-b border-zinc-100 dark:border-zinc-800">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ __('audit.th_time') }}</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ __('audit.th_event') }}</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ __('audit.th_description') }}</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400 hidden md:table-cell">{{ __('audit.th_actor') }}</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400 hidden lg:table-cell">{{ __('audit.th_ip') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800">
                    @foreach($entries as $entry)
                        <tr>
                            <td class="px-4 py-3 text-xs text-zinc-400 dark:text-zinc-500 whitespace-nowrap">{{ $entry->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-mono">{{ $entry->event }}</span>
                            </td>
                            <td class="px-4 py-3 text-zinc-800 dark:text-zinc-200">{{ $entry->description }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 hidden md:table-cell">{{ $entry->actor?->name ?? __('audit.system') }}</td>
                            <td class="px-4 py-3 text-zinc-400 dark:text-zinc-500 hidden lg:table-cell font-mono text-xs">{{ $entry->ip_address ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-zinc-50 dark:border-zinc-800">{{ $entries->links() }}</div>
        @endif
    </div>
@endsection
