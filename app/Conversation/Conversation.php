<?php

namespace App\Conversation;

use App\Conversation\Flows\WelcomeFlow;
use App\Conversation\Flows\CategoryFlow;
use App\Conversation\Context;
use App\Entities\User;
use App\Entities\Message;
use App\Traits\Loggable;
use App\Conversation\Traits\InteractWithContext;


class Conversation
{
    use Loggable, InteractWithContext;
    
    protected $flows;
    
    public function __construct(array $flows = [])
    {
        $this->flows = $flows;
    }
    
    public function start(User $user, Message $message)
    {
        $this->log('start', [
            'user' => $user->toArray(),
            'message' => $message->toArray(),
        ]);
        
        //В трейте InteractWithContext
        $this->user = $user;
        $context = $this->context();
        
        //Если в контексте есть flow - директивный запуск flow
        if($context->hasFlow()) {
            $flow = $context->getFlow();
            $flow->setUser($this->user);
            $flow->setMessage($message);
//            $this->log('flow-is-exists-in-context', [$flow]);
            $flow->handle();
            
            return;
        }
        
       
        foreach($this->flows as $flow) {
            /**
             * @var AbstractFlow $flow
             */
            $flow = app($flow);
            
            $flow->setUser($user);
            $flow->setMessage($message);
//            $this->log('flow-in-foreach', [$flow]);
            if($flow->handle()) {
                break;
            }
        }
    }
}