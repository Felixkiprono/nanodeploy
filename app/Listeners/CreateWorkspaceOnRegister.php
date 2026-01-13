<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateWorkspaceOnRegister
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
         $event->user->workspace()->create([
        'name' => 'Default Workspace',
        'description' => 'Primary NanoDeploy workspace',
    ]);
    }
}
