<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($user = $request->user()) {
            return redirect()->route(
                $user->role?->slug === 'superadmin' ? 'dashboard.index' : 'account.show'
            );
        }

        return view('home');
    }
}
