<?php

namespace iProtek\Core\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogSessionRegeneration
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        //Log::error(session('__previous_session_id'));
        //Log::error(session()->getId());
        
        \Log::error('Session Regenerated', [
            'old_id' => session('__previous_session_id'),
            'new_id' => session()->getId(),
        ]);
        
    }
}
