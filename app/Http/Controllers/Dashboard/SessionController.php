<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\SessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SessionController extends Controller
{
    public function __construct(private readonly SessionService $service) {}

    public function index(): View
    {
        return view('dashboard.sessions.index', [
            'oauthSessions' => $this->service->allOAuthSessions(),
            'webSessions'   => $this->service->allWebSessions(),
        ]);
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        $type = $request->query('type', 'oauth');

        if ($type === 'web') {
            if ($id === session()->getId()) {
                return back()->with('error', 'Tidak bisa mencabut session aktif sendiri.');
            }
            $this->service->revokeWebSession($id);
        } else {
            $this->service->revokeOAuthSession($id);
        }

        return back()->with('success', 'Session berhasil dicabut.');
    }
}
