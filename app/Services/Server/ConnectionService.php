<?php
namespace App\Services\Server;

use App\Models\Server;
use Illuminate\Support\Facades\Log;

class ConnectionService
{
    public function test(Server $server): bool
    {
        // Placeholder for now
        // Later: phpseclib / ssh binary / agent-based check

        try {
            // Fake success for now
            $connected = false;
            Log::info('Before update', [
                'server_id' => $server->id,
                'connected' => $connected,
            ]);

            $server->update([
                'connection_status' => $connected ? 'Connected' : 'Failed',
                'status' => $connected ? 'Active' : 'Inactive',
                'last_connection_checked_at' => now(),
            ]);
            $server->refresh();

            return $connected;
        } catch (\Throwable $e) {
            $server->update([
                'connection_status' => 'Failed',
                'last_connection_checked_at' => now(),
            ]);

            Log::error('Server connection failed', [
                'server_id' => $server->id,
                'exception' => $e,
            ]);

            return false;
        }
    }
}
