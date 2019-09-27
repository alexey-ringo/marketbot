<?php

namespace App\Conversation\Flows;

class CategoryFlow extends AbstractFlow
{
    protected $triggers = [
        
    ];
    
    protected $states = [
        'first',    
    ];
    
    protected function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->chat_id,
            'text' => 'Список категорий',
        ]);
        
        //$this->jump(CategoryFlow::class);
    }
    
}