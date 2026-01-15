<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerSSHKey extends Model
{
    //
     protected $fillable = [
        'server_id',
        'ssh_key_id',
        'sudo_enabled',
        'created_at',
        'updated_at',];
}
