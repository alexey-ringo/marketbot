<?php

namespace App\Conversation;

use App\Conversation\Flows\WelcomeFlow;
use App\Conversation\Flows\CategoryFlow;
use App\Conversation\Context;
use App\Entities\User;
use App\Entities\Message;

use Log;

class Conversation
{
    protected $flows = [
        WelcomeFlow::class,
        CategoryFlow::class,
    ];
    /*
    private $context;
    
    public function __construct(Context $context)
    {
        $this->context = $context;
    }
    */
    public function start(User $user, Message $message)
    {
        Log::debug('Conversation.start', [
                'user' => $user->toArray(),
                'message' => $message->toArray(),
            ]);
        
        //$context = $this->context->get($user);
        $context = Context::get($user);
            
        foreach($this->flows as $flow) {
            /**
             * @var AbstractFlow $flow
             */
            $flow = app($flow);
            
            $flow->setUser($user);
            $flow->setMessage($message);
            $flow->setContext($context);
            
            $flow->run();
        }
    }
}