<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Queries an open.mp / SA-MP server using the SA-MP UDP query protocol.
 * Based on https://github.com/Westie/samp-php
 */
class SampQueryService
{
    private string $ip;
    private int $port;
    private int $timeoutSeconds;

    /** @var resource|null */
    private mixed $socket = null;

    public function __construct(
        string $ip,
        int $port,
        int $timeoutSeconds = 5,
    ) {
        $this->ip            = $this->resolveIp($ip);
        $this->port          = $port;
        $this->timeoutSeconds = $timeoutSeconds;
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function getInfo(): ?array
    {
        $response = $this->query('i');

        if ($response === null) {
            return null;
        }

        $password   = (bool) $this->readByte($response);
        $players    = $this->readShort($response);
        $maxPlayers = $this->readShort($response);
        $hostname   = $this->readString($response);
        $gamemode   = $this->readString($response);
        $language   = $this->readString($response);

        return [
            'online'      => true,
            'hostname'    => $hostname,
            'players'     => $players,
            'max_players' => $maxPlayers,
            'gamemode'    => $gamemode,
            'language'    => $language,
            'password'    => $password,
        ];
    }

    public function getPing(): ?int
    {
        $start    = microtime(true);
        $response = $this->query('p');

        if ($response === null) {
            return null;
        }

        return (int) round((microtime(true) - $start) * 1000);
    }

    private function resolveIp(string $host): string
    {
        if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $host;
        }

        $resolved = gethostbyname($host);

        if ($resolved === $host) {
            Log::warning('SampQuery: could not resolve hostname.', ['host' => $host]);
            return $host;
        }

        Log::debug('SampQuery: resolved hostname.', ['host' => $host, 'ip' => $resolved]);

        return $resolved;
    }

    private function connect(): bool
    {
        $this->socket = @fsockopen(
            "udp://{$this->ip}",
            $this->port,
            $errorCode,
            $errorMessage,
            $this->timeoutSeconds,
        );

        if ($this->socket === false) {
            Log::debug('SampQuery: connect() failed.', [
                'ip'    => $this->ip,
                'port'  => $this->port,
                'error' => $errorMessage,
            ]);
            $this->socket = null;
            return false;
        }

        stream_set_timeout($this->socket, $this->timeoutSeconds);

        return true;
    }

    private function disconnect(): void
    {
        if ($this->socket !== null) {
            fclose($this->socket);
            $this->socket = null;
        }
    }

    private function query(string $opcode): ?string
    {
        if ($this->socket === null && ! $this->connect()) {
            return null;
        }

        $packet = $this->buildPacket($opcode);

        if (@fwrite($this->socket, $packet) === false) {
            Log::debug('SampQuery: fwrite() failed.');
            return null;
        }

        $response = @fread($this->socket, 2048);

        if ($response === false || strlen($response) < 11) {
            Log::debug('SampQuery: response too short or false.', [
                'length' => $response ? strlen($response) : 0,
            ]);
            return null;
        }

        return substr($response, 11);
    }

    private function buildPacket(string $opcode): string
    {
        $ipParts = explode('.', $this->ip);

        $packet  = 'SAMP';
        $packet .= chr((int) ($ipParts[0] ?? 0));
        $packet .= chr((int) ($ipParts[1] ?? 0));
        $packet .= chr((int) ($ipParts[2] ?? 0));
        $packet .= chr((int) ($ipParts[3] ?? 0));
        $packet .= chr($this->port & 0xFF);
        $packet .= chr(($this->port >> 8) & 0xFF);
        $packet .= $opcode;

        return $packet;
    }

    private function readByte(string &$buffer): int
    {
        $value  = ord($buffer[0]);
        $buffer = substr($buffer, 1);
        return $value;
    }

    private function readShort(string &$buffer): int
    {
        $value  = unpack('v', substr($buffer, 0, 2))[1];
        $buffer = substr($buffer, 2);
        return $value;
    }

    private function readString(string &$buffer): string
    {
        $length = unpack('V', substr($buffer, 0, 4))[1];
        $buffer = substr($buffer, 4);
        $value  = substr($buffer, 0, $length);
        $buffer = substr($buffer, $length);
        return $value;
    }
}