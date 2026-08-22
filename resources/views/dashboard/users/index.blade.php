@extends('layouts.dashboard')
@section('title', __('users.title'))
@section('heading', __('users.title'))

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('dashboard.users.invite') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" /></svg>
            {{ __('users.invite_button') }}
        </a>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 dark:bg-zinc-800/60 border-b border-zinc-100 dark:border-zinc-800">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ __('users.th_user') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400 hidden sm:table-cell">{{ __('users.th_role') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ __('users.th_status') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400 hidden md:table-cell">{{ __('users.th_joined') }}</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800">
                @forelse($users as $user)
                    <tr>
                        <td class="px-5 py-3">
                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500">{{ $user->email }}</p>
                        </td>
                        <td class="px-5 py-3 hidden sm:table-cell">
                            <form method="POST" action="{{ route('dashboard.users.role', $user->id) }}">
                                @csrf @method('PATCH')
                                <div x-data="{
                                        open: false,
                                        value: {{ $user->role_id }},
                                        options: [
                                            @foreach($roles as $role)
                                                { value: {{ $role->id }}, label: @js($role->name) },
                                            @endforeach
                                        ],
                                        get current() { return this.options.find(o => o.value === this.value); },
                                        select(opt) {
                                            this.open = false;
                                            if (opt.value === this.value) return;
                                            this.value = opt.value;
                                            $el.closest('form').submit();
                                        }
                                     }"
                                     @click.outside="open = false"
                                     class="relative w-32">
                                    <input type="hidden" name="role_id" :value="value">
                                    <button type="button" @click="open = !open"
                                            class="w-full flex items-center justify-between gap-1 px-2 py-1 text-xs border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 hover:border-zinc-400 dark:hover:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 dark:focus:ring-zinc-100/10 transition-shadow">
                                        <span x-text="current.label"></span>
                                        <svg class="w-3.5 h-3.5 text-zinc-400 shrink-0 transition-transform duration-150" :class="open ? 'rotate-180' : ''"
                                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                    <div x-show="open" x-transition x-cloak
                                         class="absolute z-10 mt-1 w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg py-1">
                                        <template x-for="opt in options" :key="opt.value">
                                            <button type="button" @click="select(opt)"
                                                    class="w-full text-left px-2.5 py-1.5 text-xs hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors"
                                                    :class="value === opt.value ? 'text-zinc-900 dark:text-zinc-100 font-medium bg-zinc-50 dark:bg-zinc-700' : 'text-zinc-600 dark:text-zinc-300'">
                                                <span x-text="opt.label"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </form>
                        </td>
                        <td class="px-5 py-3">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300">{{ __('users.active') }}</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300">{{ __('users.inactive') }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-zinc-400 dark:text-zinc-500 text-xs hidden md:table-cell">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <form method="POST" action="{{ route('dashboard.users.toggle-active', $user->id) }}">
                                @csrf @method('PATCH')
                                @if($user->is_active)
                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition-colors">{{ __('users.deactivate') }}</button>
                                @else
                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 text-zinc-600 dark:text-zinc-400 text-xs font-medium rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">{{ __('users.activate') }}</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center">
                            <p class="text-sm text-zinc-400 dark:text-zinc-500">{{ __('users.no_users') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t border-zinc-50 dark:border-zinc-800">{{ $users->links() }}</div>
    </div>
@endsection
