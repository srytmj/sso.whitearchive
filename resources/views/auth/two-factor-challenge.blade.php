@extends('layouts.auth')

@section('title', __('two_factor.challenge_title'))
@section('subtitle', __('two_factor.challenge_subtitle'))

@section('content')
    <p class="text-sm text-base-content/60 mb-6">{{ __('two_factor.challenge_instruction') }}</p>

    <form method="POST" action="{{ route('two-factor.verify') }}" novalidate>
        @csrf
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('two_factor.code_label') }}</label>
                <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" autofocus
                    class="input input-bordered w-full" />
                @error('code')
                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-neutral w-full active:scale-95">{{ __('two_factor.verify_button') }}</button>
        </div>
    </form>
@endsection
