<?php

namespace App\Conversation\Flows;

use App\Conversation\Traits\HasTriggers;
use App\Conversation\Traits\HasStates;
use App\Conversation\Traits\SendMessages;

class WelcomeFlow extends AbstractFlow
{
    use HasTriggers, HasStates, SendMessages;
    
    public function __construct()
    {
        //лучше хранить триггеры в БД!
        $this
            ->addTrigger('/start')
            ->addTrigger('привет');
        
        $this
            ->addState('sayHello');
    }
    
    protected function sayHello()
    {
        $this->log('sayHello');
        
        $this->reply('Добро пожаловать в наш магазин "' . config('app.name') . '"!');
        
        $this->runFlow(CategoryFlow::class);
    }
}