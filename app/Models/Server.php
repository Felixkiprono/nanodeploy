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
        'status'
    ];

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }
}
