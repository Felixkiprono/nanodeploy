<?php

namespace App\Filament\Resources\SSHKeys\Pages;

use App\Filament\Resources\SSHKeys\SSHKeyResource;
use Filament\Resources\Pages\Page;
use App\Models\SSHKey;
use App\Models\Server;
use App\Services\SSH\SSHKeyGenerator;
use Illuminate\Support\Facades\DB;

class GenerateSSHKey extends Page
{
    protected static string $resource = SSHKeyResource::class;

    protected  string $view = 'filament.resources.ssh-keys.generate';

    public ?SSHKey $sshKey = null;

    protected ?Server $server = null;

    public function mount(SSHKeyGenerator $generator): void
    {
        $keys = $generator->generate();
        if (request()->filled('server')) {
            $this->server = Server::find(request('server'));
        }

        $sshKey = SSHKey::create([
            'name' => 'NanoDeploy System Key',
            'type' => 'system',
            'public_key' => $keys['public_key'],
            'private_key' => $keys['private_key'],
        ]);
        $this->sshKey = $sshKey;

        if ($this->server) {
            DB::transaction(function () use ($sshKey) {
                // Deactivate existing active keys
                $this->server->sshKeys()
                    ->wherePivot('active', true)
                    ->update(['active' => false]);

                // Attach new key as active
                $this->server->sshKeys()->attach($sshKey->id, [
                    'active' => true,
                ]);
            });
        }
    }
}
