<?php

namespace App\Conversation\Flows;

class WelcomeFlow extends AbstractFlow
{
    protected $triggers = [
        '/start',
        'привет',
    ];
    
    protected $states = [
        'first',    
    ];
    
    protected function first()
    {
         \Log::debug('WelcomeFlow.first', [
                'states' => $this->states,
                'microtime' => microtime(true),
            ]);
            
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->chat_id,
            'text' => 'Добро пожаловать в наш магазин "' . config('app.name') . '"!',
        ]);
        
        $state = 'first';
        $this->jump(CategoryFlow::class, $state);
    }
}