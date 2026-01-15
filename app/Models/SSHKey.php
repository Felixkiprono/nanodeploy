<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SSHKey extends Model
{
    //
      protected $fillable = [
        'name',
        'type',
        'public_key',
        'private_key',
        'fingerprint',
        'owner_user_id',
        'created_at',
        'revoked_at',
    ];
}
