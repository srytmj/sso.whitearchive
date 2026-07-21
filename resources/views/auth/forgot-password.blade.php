@extends('layouts.auth')

@section('title', 'Forgot Password')
@section('subtitle', 'Reset your password')

@section('content')
    @if(session('status'))
        <div class="flex items-start gap-3 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm mb-6">
            {{ session('status') }}
        </div>
    @endif

    <p class="text-sm text-zinc-500 mb-6">
        Masukkan email kamu dan kami akan mengirimkan link untuk reset password.
    </p>

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" autofocus
                    class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent" />
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-800 transition-colors">Send Reset Link</button>
        </div>
    </form>

    <hr class="border-zinc-200 my-6">

    <p class="text-sm text-center text-zinc-500">
        Ingat password kamu?
        <a href="{{ route('login') }}" class="text-zinc-900 font-medium hover:underline">Sign in</a>
    </p>
@endsection
