<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ServerStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServerStatusController extends Controller
{
    public function __construct(
        private readonly ServerStatusService $serverStatus,
    ) {}

    /**
     * GET /api/v1/server/status
     *
     * Returns the current server status (cached, refreshed every 30s).
     */
    public function show(): JsonResponse
    {
        $status = $this->serverStatus->get();

        return response()->json($status);
    }

    /**
     * POST /api/v1/server/status/refresh
     *
     * Forces a fresh query, bypassing the cache.
     * Restricted to internal use (e.g. cron, admin).
     */
    public function refresh(): JsonResponse
    {
        $status = $this->serverStatus->refresh();

        return response()->json($status);
    }
}