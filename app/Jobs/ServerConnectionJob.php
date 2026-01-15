<?php

namespace App\Jobs;

use App\Models\Server;
use App\Services\Server\ConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;
use App\Services\Action\Shell\ShellService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ServerConnectionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Server $server
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $task = Task::create([
            'server_id' => $this->server->id,
            'type' => 'ssh_test',
            'status' => 'running',
            'started_at' => now(),
        ]);

        try {
            $shell = new ShellService($this->server, $task);

            $output = trim($shell->exec('whoami'));
            Log::info('SSH whoami output', [$output]);
            if ($output !== $this->server->username) {
                throw new \RuntimeException(
                    "SSH connected, but user mismatch: {$output}"
                );
            }

            $task->update([
                'status' => 'success',
                'finished_at' => now(),
            ]);

            $this->server->update([
                'connection_status' => 'Connected',
                'last_connection_checked_at' => now(),
            ]);
        } catch (\Throwable $e) {
            $task->log($e->getMessage(), 'error');

            $task->update([
                'status' => 'failed',
                'finished_at' => now(),
            ]);

            $this->server->update([
                'connection_status' => 'Failed',
                'last_connection_checked_at' => now(),
            ]);
        }
    }
}
