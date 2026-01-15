<?php

namespace App\Services\SSH;

use Illuminate\Support\Str;

class SSHKeyGenerator
{
    public function generate(): array
    {
        $tempDir = storage_path('app/ssh-temp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0700, true);
        }

        $keyName = 'nanodeploy_' . Str::random(8);
        $privateKeyPath = "{$tempDir}/{$keyName}";
        $publicKeyPath = "{$privateKeyPath}.pub";

        exec(sprintf(
            'ssh-keygen -t ed25519 -f %s -N "" -C "nanodeploy"',
            escapeshellarg($privateKeyPath)
        ));

        return [
            'private_key' => file_get_contents($privateKeyPath),
            'public_key' => file_get_contents($publicKeyPath),
        ];
    }
}
