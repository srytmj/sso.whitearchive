@extends('layouts.auth')

@section('title', __('oauth.title'))
@section('subtitle', __('oauth.subtitle'))

@section('content')
    <p class="text-sm text-zinc-700 dark:text-zinc-300 mb-5">
        {!! __('oauth.requesting_access', ['app' => '<strong>' . e($client->name) . '</strong>']) !!}
    </p>

    @if(count($scopes) > 0)
        <div class="mb-6">
            <p class="text-xs uppercase tracking-wide font-medium text-zinc-500 dark:text-zinc-400 mb-2">{{ __('oauth.will_be_able_to') }}</p>
            <ul class="space-y-1.5">
                @foreach($scopes as $scope)
                    <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                        <svg class="w-4 h-4 text-blue-500 dark:text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        {{ $scope->description }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex gap-3">
        <form method="POST" action="/oauth/authorize" class="flex-1">
            @csrf
            <input type="hidden" name="state" value="{{ $request->state }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">
            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-800 dark:hover:bg-white transition-colors">{{ __('oauth.authorize_button') }}</button>
        </form>

        <form method="POST" action="/oauth/authorize" class="flex-1">
            @csrf
            @method('DELETE')
            <input type="hidden" name="state" value="{{ $request->state }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">
            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 text-zinc-600 dark:text-zinc-400 text-sm font-medium rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">{{ __('oauth.deny_button') }}</button>
        </form>
    </div>
@endsection
