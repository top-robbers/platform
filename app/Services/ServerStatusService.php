<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ServerStatusService
{
    private const CACHE_KEY = 'server_status';
    private const CACHE_TTL_SECONDS = 30;

    public function __construct(
        private readonly SampQueryService $query,
    ) {}

    /**
     * Returns the cached server status, refreshing it if stale.
     *
     * @return array{
     *     online: bool,
     *     players: int,
     *     max_players: int,
     *     hostname: string,
     *     gamemode: string,
     *     language: string,
     *     password: bool,
     *     ping: int|null,
     *     cached_at: string,
     * }
     */
    public function get(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            return $this->fetch();
        });
    }

    /**
     * Forces a fresh query, bypassing the cache.
     */
    public function refresh(): array
    {
        $status = $this->fetch();
        Cache::put(self::CACHE_KEY, $status, self::CACHE_TTL_SECONDS);
        return $status;
    }

    private function fetch(): array
    {
        try {
            $info = $this->query->getInfo();
            $ping = $this->query->getPing();
        } catch (\Throwable $e) {
            Log::warning('Failed to query SA-MP server.', [
                'error' => $e->getMessage(),
            ]);

            $info = null;
            $ping = null;
        }

        if ($info === null) {
            return [
                'online' => false,
                'players' => 0,
                'max_players' => 0,
                'hostname' => '',
                'gamemode' => '',
                'language' => '',
                'password' => false,
                'ping' => null,
                'cached_at' => now()->toIso8601String(),
            ];
        }

        return [
            ...$info,
            'ping' => $ping,
            'cached_at' => now()->toIso8601String(),
        ];
    }
}