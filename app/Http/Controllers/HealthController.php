<?php

namespace App\Http\Controllers;

use App\Services\HealthCheckService;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    public function __construct(private readonly HealthCheckService $healthCheckService)
    {
    }

    public function check(): JsonResponse
    {
        $result = $this->healthCheckService->check();

        return response()->json($result, $result['status'] === 'ok' ? 200 : 503);
    }
}
