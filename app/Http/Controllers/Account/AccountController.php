<?php

namespace App\Http\Controllers\Account;

use App\Actions\Account\ChangePasswordAction;
use App\Actions\Account\RevokeSessionAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct(
        private readonly ChangePasswordAction $changePasswordAction,
        private readonly RevokeSessionAction $revokeSessionAction,
    ) {}

    public function show(Request $request): View
    {
        return view('account.show', ['user' => $request->user()]);
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $this->changePasswordAction->execute(
            $user,
            $validated['current_password'],
            $validated['new_password'],
        );

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function sessions(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $tokens = $user->tokens()
            ->where('revoked', false)
            ->where('expires_at', '>', now()->toDateTimeString())
            ->with('client')
            ->orderByDesc('created_at')
            ->get();

        return view('account.sessions', compact('tokens'));
    }

    public function revokeSession(Request $request, string $tokenId): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->revokeSessionAction->execute($user, $tokenId);

        return back()->with('success', 'Session berhasil dicabut.');
    }

    public function revokeAllSessions(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->revokeSessionAction->revokeAll($user);

        return back()->with('success', 'Semua session berhasil dicabut.');
    }
}
