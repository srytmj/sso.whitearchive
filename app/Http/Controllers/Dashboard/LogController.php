<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\LogViewerService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogController extends Controller
{
    public function __construct(private readonly LogViewerService $service) {}

    public function index(Request $request): View
    {
        $level = $request->query('level');
        $search = $request->query('q');
        $page = max(1, (int) $request->query('page', 1));

        return view('dashboard.logs.index', [
            'entries' => $this->service->paginate($level, $search, $page),
            'levels' => LogViewerService::LEVELS,
            'level' => $level,
            'search' => $search,
        ]);
    }
}
