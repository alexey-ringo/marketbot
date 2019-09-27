<?php

namespace App\Listeners\Conversation;

use App\Conversation\Context;
use App\Events\Conversation\onFlowRunned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Log;

class SaveRunnedFlowToContext
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
     * @param  onFlowRunned  $event
     * @return void
     */
    public function handle(onFlowRunned $event)
    {
        $user = $event->getUser(); 
        $flow = $event->getFlow(); 
        $state = $event->getState();
        
        //Log::debug('SaveRunnedFlowToContext.handle', [
        //    'user' => $user->toArray(),
        //    'flow' => get_class($flow),
        //    'state' => $state,  
        //    'microtime' => microtime(true),
        //]);
        
        Context::save($user, $flow, $state);
    }
}
