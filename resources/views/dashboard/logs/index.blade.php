@extends('layouts.dashboard')
@section('title', __('dashboard.logs_heading'))
@section('heading', __('dashboard.logs_heading'))

@section('content')
    <div class="card bg-base-100 border border-base-300 mb-6">
        <div class="card-body p-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <form method="GET" class="flex-1">
                    <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('dashboard.logs_search_placeholder') }}"
                           class="input w-full">
                    @if($level)
                        <input type="hidden" name="level" value="{{ $level }}">
                    @endif
                </form>

                <div x-data="{
                        open: false,
                        value: @js($level ?? ''),
                        options: [
                            { value: '', label: @js(__('dashboard.logs_all_levels')) },
                            @foreach($levels as $lvl)
                                { value: @js($lvl), label: @js(ucfirst($lvl)) },
                            @endforeach
                        ],
                        get current() { return this.options.find(o => o.value === this.value) ?? this.options[0]; },
                        select(opt) {
                            this.open = false;
                            if (opt.value === this.value) return;
                            this.value = opt.value;
                            const url = new URL(window.location.href);
                            if (opt.value) { url.searchParams.set('level', opt.value); } else { url.searchParams.delete('level'); }
                            url.searchParams.delete('page');
                            window.location.href = url.toString();
                        }
                     }"
                     @click.outside="open = false"
                     class="relative w-full sm:w-48 shrink-0">
                    <button type="button" @click="open = !open"
                            class="btn btn-outline w-full flex items-center justify-between gap-2 active:scale-95">
                        <span x-text="current.label"></span>
                        <svg class="w-4 h-4 text-base-content/60 shrink-0 transition-transform duration-150" :class="open ? 'rotate-180' : ''"
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="menu absolute z-10 mt-1 w-full bg-base-100 border border-base-300 rounded-box shadow-md py-1 max-h-64 overflow-y-auto flex-nowrap">
                        <template x-for="opt in options" :key="opt.value">
                            <button type="button" @click="select(opt)"
                                    class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 text-sm rounded-lg hover:bg-base-200 transition-colors"
                                    :class="value === opt.value ? 'text-base-content font-medium bg-base-200' : 'text-base-content/70'">
                                <span x-text="opt.label"></span>
                                <svg x-show="value === opt.value" class="w-3.5 h-3.5 text-base-content shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </button>
                        </template>
                    </div>
                </div>

                @if($level || $search)
                    <a href="{{ route('dashboard.logs.index') }}" class="px-3 py-2 text-sm text-base-content/60 hover:text-base-content transition-colors shrink-0 self-center">
                        {{ __('common.reset') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="card bg-base-100 border border-base-300">
        <div class="flex items-center justify-between px-4 py-3 border-b border-base-200">
            <p class="text-sm text-base-content/60">{{ __('dashboard.logs_entries_found', ['count' => $entries->total()]) }}</p>
        </div>

        @if($entries->isEmpty())
            <div class="px-6 py-16 text-center">
                <p class="text-sm text-base-content/60">{{ __('dashboard.logs_no_match') }}</p>
            </div>
        @else
            <div class="divide-y divide-base-200">
                @foreach($entries as $i => $entry)
                    @php
                        $badgeClass = match($entry['level']) {
                            'emergency', 'alert', 'critical', 'error' => 'badge-error',
                            'warning' => 'badge-warning',
                            'notice', 'info' => 'badge-info',
                            default => 'badge-neutral',
                        };
                        $lines = explode("\n", $entry['message']);
                        $firstLine = $lines[0];
                        $hasMore = count($lines) > 1;
                    @endphp
                    <div x-data="{ open: false }" class="px-4 py-3">
                        <div class="flex items-start gap-3 {{ $hasMore ? 'cursor-pointer' : '' }}"
                             @if($hasMore) @click="open = !open" @endif>
                            <span class="badge {{ $badgeClass }} badge-soft shrink-0">
                                {{ strtoupper($entry['level']) }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-base-content/60 mb-0.5">{{ $entry['timestamp'] }}</p>
                                <p class="text-sm text-base-content font-mono break-all">{{ $firstLine }}</p>
                            </div>
                            @if($hasMore)
                                <svg class="w-4 h-4 text-base-content/60 shrink-0 mt-1 transition-transform duration-150" :class="open ? 'rotate-90' : ''"
                                     fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            @endif
                        </div>
                        @if($hasMore)
                            <div x-show="open" x-transition x-cloak class="mt-2 ml-[3.75rem]">
                                <pre class="text-xs text-base-content/80 bg-base-200 border border-base-300 rounded-lg p-3 overflow-x-auto whitespace-pre-wrap">{{ $entry['message'] }}</pre>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="px-5 py-3 border-t border-base-200">{{ $entries->links() }}</div>
        @endif
    </div>
@endsection
