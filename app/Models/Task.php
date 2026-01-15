<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
     protected $fillable = [
        'server_id',
        'type',
        'status',
        'meta',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function logs()
    {
        return $this->hasMany(TaskLog::class);
    }

    public function log(string $message, string $level = 'info'): void
    {
        $this->logs()->create([
            'level' => $level,
            'message' => $message,
        ]);
    }
}
