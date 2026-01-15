<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Server extends Model
{
    //
    protected $fillable = [
        'workspace_id',
        'name',
        'host',
        'port',
        'username',
        'ssh_key_path',
        'status',
        'connection_status',
        'last_connection_checked_at'
    ];

    // public enum ConnectionStatus: string
    // {
    //     case Unknown = 'Unknown';
    //     case Connected = 'Connected';
    //     case Disconnected = 'Disconnected';
    // }

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function getPublicIpAttribute(): ?string
    {
        return $this->host;
    }
    public function sshKeys()
    {
        return $this->belongsToMany(
            SSHKey::class,
            'server_ssh_keys',
            'server_id',
            'ssh_key_id'
        )
            ->withPivot(['active'])
            ->withTimestamps();
    }

    public function maskedActiveSshKey(): ?string
    {
        $key = optional($this->activeSshKey()->first())->public_key;

        if (! $key) {
            return null;
        }

        [$type, $body, $comment] = array_pad(explode(' ', $key, 3), 3, '');

        return sprintf(
            '%s %s…%s %s',
            $type,
            substr($body, 0, 10),
            substr($body, -6),
            $comment
        );
    }


    public function activeSshKey()
    {
        return $this->sshKeys()->wherePivot('active', true);
    }
}
