<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SSHkey extends Model
{
    //
     use SoftDeletes;

    protected $table = 'ssh_keys';

    protected $fillable = [
        'name',
        'type',
        'public_key',
        'private_key',
        'fingerprint',
    ];

    public function servers()
    {
        return $this->belongsToMany(Server::class, 'server_ssh_keys')
            ->withPivot(['active'])
            ->withTimestamps();
    }

}
