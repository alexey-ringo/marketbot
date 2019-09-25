<?php

namespace App\Conversation\Flows;

use App\Entities\User;
use App\Entities\Message;
use Telegram;
use Telegram\Bot\Api;

use Log;

abstract class AbstractFlow
{
    /**
     * @var User
     * 
     */
    protected $user;
    
    /**
     * @var Message
     * 
     */
    protected $message;
    
    /**
     * @var array
     * 
     */
    protected $triggers = [];
    
    /**
     * @var array
     * 
     */
    protected $states = [];
    
    
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    
    public function setMessage(Message $message)
    {
        $this->message = $message;
    }
    
    /**
     * @param string|null $state
     */
    public function run($state = null)
    {
        //statuc::class - отдаст название класса ...Flow, которое запускается в текущем контексте
        //если self::class - то AbstractFlow в любом случае
        Log::debug(static::class . '.run', [
            'user' => $this->user->toArray(),
            'message' => $this->message->toArray(),
            'state' => $state
        ]);
        
        foreach($this->triggers as $trigger) {
            if(hash_equals($trigger, $this->message->text)) {
                $this->first();
            }
        }
    }
    
    protected function telegram(): Api
    {
        return Telegram::bot();
    }
    
    abstract protected function first();
}