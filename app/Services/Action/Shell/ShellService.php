<?php

namespace App\Services\Action\Shell;

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;
use App\Models\Server;
use App\Models\Task;
use App\Models\SSHKey;
use App\Models\ServerSshKey;


class ShellService
{
    protected SSH2 $ssh;
    protected Task $task;

    public function __construct(Server $server, Task $task)
    {
        $this->task = $task;

        $this->ssh = new SSH2(
            $server->host,
            $server->port ?? 22
        );
        $serverSshKey = ServerSshKey::where('server_id', $server->id)
            ->where('active', true)
            ->first();
        if (! $serverSshKey) {
            throw new \RuntimeException('No active SSH key found for server.');
        }
        $sshkey = SSHKey::where('id', $serverSshKey->ssh_key_id)->first();
        //the keys are SSHKey model not server
        $privateKey = $sshkey->private_key;
        $publicKey = $sshkey->public_key;
        $key = PublicKeyLoader::load($privateKey);

        if (! $this->ssh->login($server->username, $key)) {
            throw new \RuntimeException('SSH authentication failed.');
        }

        $this->task->log('SSH connection established.');
    }

    public function exec(string $command): string
    {
        $this->task->log("Executing: {$command}");

        $output = $this->ssh->exec($command);

        if ($output === false) {
            $this->task->log('Command execution failed.', 'error');
            throw new \RuntimeException('Command execution failed.');
        }

        $this->task->log(trim($output));

        return $output;
    }
}
