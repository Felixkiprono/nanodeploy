<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Site extends Model
{
    //
      protected $fillable = [
        'server_id',
        'name',
        'domain',
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}
