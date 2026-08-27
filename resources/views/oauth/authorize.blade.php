@extends('layouts.auth')

@section('title', __('oauth.title'))
@section('subtitle', __('oauth.subtitle'))

@section('content')
    <p class="text-sm text-base-content/70 mb-5">
        {!! __('oauth.requesting_access', ['app' => '<strong>' . e($client->name) . '</strong>']) !!}
    </p>

    @if(count($scopes) > 0)
        <div class="mb-6">
            <p class="text-xs uppercase tracking-wide font-medium text-base-content/60 mb-2">{{ __('oauth.will_be_able_to') }}</p>
            <ul class="space-y-1.5">
                @foreach($scopes as $scope)
                    <li class="flex items-center gap-2 text-sm text-base-content/70">
                        <svg class="w-4 h-4 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
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
            <button type="submit" class="btn btn-primary w-full active:scale-95">{{ __('oauth.authorize_button') }}</button>
        </form>

        <form method="POST" action="/oauth/authorize" class="flex-1">
            @csrf
            @method('DELETE')
            <input type="hidden" name="state" value="{{ $request->state }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">
            <button type="submit" class="btn btn-ghost w-full active:scale-95">{{ __('oauth.deny_button') }}</button>
        </form>
    </div>
@endsection
