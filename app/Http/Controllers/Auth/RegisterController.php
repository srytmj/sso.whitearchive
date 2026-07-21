<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\RegisterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(private readonly RegisterService $registerService) {}

    public function show(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username', 'regex:/^[a-z0-9_]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $this->registerService->register($validated);

        Auth::login($user);

        $request->session()->regenerate();

        $default = $user->role?->slug === 'superadmin'
            ? route('dashboard.index')
            : route('account.show');

        return redirect()->intended($default);
    }
}
