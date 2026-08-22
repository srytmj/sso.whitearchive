<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function __construct(private readonly AuditLogService $service) {}

    public function index(Request $request): View
    {
        $event = $request->query('event');
        $search = $request->query('q');
        $page = max(1, (int) $request->query('page', 1));

        return view('dashboard.audit-log.index', [
            'entries' => $this->service->paginate($event, $search, $page),
            'events' => AuditLogService::EVENTS,
            'event' => $event,
            'search' => $search,
        ]);
    }
}
