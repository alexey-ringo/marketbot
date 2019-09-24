<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\MessageRepository;
use Telegram;

use Log;

class TelegramController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function process(UserRepository $users, MessageRepository $messages)
    {
        $update = Telegram::bot()->getWebhookUpdate();
        
        Log::debug('Telegram.process', [
                'update' => $update,
            ]);
        
        $message = $update->getMessage();
        $tlgUser = $message->getFrom();
        
        
        //Созраняем пользователя
        $user = $users->store(
            $tlgUser->getId(), 
            $tlgUser->getFirstName() ?? '', 
            $tlgUser->getLastName() ?? '', 
            $tlgUser->getUsername() ?? ''
        );
      
        //Сохраняем сообщение
        $messages->store(
            $user,
            $message->getMessageId(),
            $message->getText() ?? ''
        );
        
        if(hash_equals($message->getText(), '/start')) {
            Telegram::bot()->sendMessage([
                'chat_id' => $user->chat_id,
                'text' => 'Добро пожаловать в магазин Алекса!'
            ]);
        }
    }
}
