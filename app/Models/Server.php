<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    //
    protected $fillable = [
        'workspace_id',
        'name',
        'host',
        'port',
        'username',
        'ssh_key_path'
    ];
}
