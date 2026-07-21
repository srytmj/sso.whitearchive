<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\OAuth\Client;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users_active' => User::active()->count(),
            'users_total' => User::count(),
            'clients' => Client::count(),
        ];

        $clients = Client::orderByDesc('created_at')->get();

        return view('dashboard.index', compact('stats', 'clients'));
    }
}
