<?php

namespace App\Conversation\Traits;

use App\Entities\User;
use Telegram;
use Telegram\Bot\Keyboard\Button;
use Telegram\Bot\Keyboard\Keyboard;

trait SendMessages
{
    /**
     * @var User
     */
    protected $user;
    
    protected function reply(string $message, array $buttons = [])
    {
        $params = [
            'chat_id' => $this->user->chat_id,
            'text' => $message,
        ];
        
        if(count($buttons) > 0) {
            $buttons = collect($buttons)->map(function($value) {
                return [$value];
            });
            
            $params['reply_markup'] = Keyboard::make([
                'keyboard' => $buttons->toArray(), 
                'resize_keyboards' => true, 
                'one_time_keyboard' => true
            ]);
        }
        
        Telegram::bot()->sendMessage($params);
    }
    
}