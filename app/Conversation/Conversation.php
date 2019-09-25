<?php

namespace App\Conversation;

use App\Conversation\Flows\WelcomeFlow;
use App\Entities\User;
use App\Entities\Message;

use Log;

class Conversation
{
    protected $flows = [
        WelcomeFlow::class,
    ];
    public function start(User $user, Message $message)
    {
        Log::debug('Conversation.start', [
                'user' => $user->toArray(),
                'message' => $message->toArray(),
            ]);
            
        foreach($this->flows as $flow) {
            /**
             * @var AbstractFlow $flow
             */
            $flow = app($flow);
            $flow->setUser($user);
            $flow->setMessage($message);
            
            $flow->run();
        }
    }
}