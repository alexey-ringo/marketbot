<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\MessageRepository;
use App\Conversation\Conversation;

use Telegram;

use Log;

class TelegramController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function process(
        UserRepository $users, 
        MessageRepository $messages,
        Conversation $conversation
        )
    {
        $update = Telegram::bot()->getWebhookUpdate();
        
        Log::debug('Telegram.process', [
                'update' => $update,
            ]);
        
        $tlgMessage = $update->getMessage();
        $tlgUser = $tlgMessage->getFrom();
        
        
        //Созраняем пользователя
        $user = $users->store(
            $tlgUser->getId(), 
            $tlgUser->getFirstName() ?? '', 
            $tlgUser->getLastName() ?? '', 
            $tlgUser->getUsername() ?? ''
        );
      
        //Сохраняем сообщение
        $message = $messages->store(
            $user,
            $tlgMessage->getMessageId(),
            $tlgMessage->getText() ?? ''
        );
        
        $conversation->start($user, $message);
        
    //    if(hash_equals($tlgMessage->getText(), '/start')) {
    //        Telegram::bot()->sendMessage([
    //            'chat_id' => $user->chat_id,
    //            'text' => 'Добро пожаловать в магазин Алекса!'
    //        ]);
    //    }
    
    }
}
