<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RevokeTokenAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __construct(private readonly RevokeTokenAction $revokeTokenAction) {}

    public function destroy(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user) {
            $this->revokeTokenAction->execute($user);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
