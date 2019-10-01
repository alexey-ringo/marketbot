<?php

namespace App\Listeners\Conversation;

use App\Conversation\Context;
use App\Events\Conversation\onOptionChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateOptionInContext
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
     * @param  onOptionChanged  $event
     * @return void
     */
    public function handle(onOptionChanged $event)
    {
        $user = $event->getUser();
        $key = $event->getKey();
        $value = $event->getValue();
        \Log::debug('UpdateOptionInContext.handle', [
            'user' => $user->toArray(),
            'key' => $key,
            'value' => $value,            
            'microtime' => microtime(true),
        ]);
        
        Context::update($user, [$key => $value]);
    }
}
