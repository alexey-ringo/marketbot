<?php

namespace App\Conversation\Flows;

use App\Entities\User;
use App\Entities\Message;
use App\Conversation\Context;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Telegram;
use Telegram\Bot\Api;
use Event;
use App\Events\Conversation\onFlowRunned;
use Log;

abstract class AbstractFlow
{
    /**
     * @var User
     */
    protected $user;
    
    /**
     * @var Message 
     */
    protected $message;
    
    /**
     * @var array 
     */
    protected $triggers = [];
    
    /**
     * @var array 
     */
    protected $states = [];
    
    /**
     * @var array 
     */
    protected $context = [];
    
    
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    
    public function setMessage(Message $message)
    {
        $this->message = $message;
    }
    
    public function setContext(array $context)
    {
        $this->context = $context;
    }
    
    public function getStates(): array
    {
        return $this->states;
    }
    
    /**
     * @param string|nill $state
     * @return bool
     */
    public function run($state = null): bool
    {
        //statuc::class - отдаст название класса ...Flow, которое запускается в текущем контексте
        //если self::class - то AbstractFlow в любом случае
        Log::debug(static::class . '.run', [
            'user' => $this->user->toArray(),
            'message' => $this->message->toArray(),
            'state' => $state,
            'microtime' => microtime(true),
        ]);
        
        //Если в параметре уже было передано значение $state
        if(!is_null($state)) {
            
            Log::debug(static::class . '.run.Если в параметре было передано значение $state', [
                'user' => $this->user->toArray(),
                'message' => $this->message->toArray(),
                'state' => $state,
                'microtime' => microtime(true),
            ]);
            
            Event::dispatch(new onFlowRunned($this->user, $this, $state));
            $this->$state();
            
            return true;
        }
        
        //Поиск по контексту
        $state = $this->findByContext();
        if(!is_null($state)) {
            
            Log::debug(static::class . '.run.Поиск по контексту', [
                'user' => $this->user->toArray(),
                'message' => $this->message->toArray(),
                'state' => $state,
                'microtime' => microtime(true),
            ]);
            
            Event::dispatch(new onFlowRunned($this->user, $this, $state));
            $this->$state();
            
            return true;
        }
        
        //Поиск по триггерам
        $state = $this->findByTrigger();
        if(!is_null($state)) {
            
            Log::debug(static::class . '.run.Поиск по триггерам', [
                'user' => $this->user->toArray(),
                'message' => $this->message->toArray(),
                'state' => $state,
                'microtime' => microtime(true),
            ]);
            
            Event::dispatch(new onFlowRunned($this->user, $this, $state));
            $this->$state();
            
            return true;
        }
        
        Log::debug(static::class . '.run.return false', [
            'user' => $this->user->toArray(),
            'message' => $this->message->toArray(),
            'state' => $state,
            'microtime' => microtime(true),
        ]);
        
        return false;
    }
    
    private function findByContext(): ?string
    {
        Log::debug(static::class . '.run.findByContext()', [
            'context' => $this->context,
            'microtime' => microtime(true),
        ]);
        
        $state = null;
        
        if(
            isset($this->context['flow']) &&
            isset($this->context['state']) &&
            class_exists($this->context['flow']) &&
            method_exists(app($this->context['flow']), $this->context['state'])
        ) {
            /**
             * @var AbstractFlow $flow
             */
            $flow = $this->getFlow($this->context['flow']);
            
            /** @var array $states */
            $states = $flow->getStates();
            //Если в дочернем Flow-классе забыли прописать и заполнить массив $states
            if(empty($states)) {
                return null;
            }
            //key (index) from collection
            $currentStateIndex = collect($states)->search($this->context['state']);
            Log::debug(static::class . '.run.findByContext()-currentStateIndex', [
                'flow' => $flow,
                'states' => $states,
                'currentStateIndex' => $currentStateIndex,
                'microtime' => microtime(true),
            ]);
            $currentState = $states[$currentStateIndex];
            
            //Проверка на наличие следующего state
            if(isset($states[$currentStateIndex + 1])) {
                $nextState = $states[$currentStateIndex + 1];
                //Если есть - то запускаем и его
                $flow->run($nextState);
                
                return $nextState;
            }
        }
        
        return null;
    }
    
    private function findByTrigger()
    {
        $state = null;
        
        foreach($this->triggers as $trigger) {
            if(hash_equals($trigger, $this->message->text)) {
                $state = 'first';
            }
        }
        
        return $state;
    }
    
    
    protected function telegram(): Api
    {
        return Telegram::bot();
    }
    
    protected function jump(string $flow, string $state = null)
    {
        //Получаем созданное в getFlow() новое Flow и запускаем его метод (имя метода в $state)
        $this->getFlow($flow)->run($state);
    }
    
    private function getFlow(string $flow): AbstractFlow
    {
        if(!class_exists($flow)) {
            throw new InvalidArgumentException('Flow does not exist');
        }
        
        /**
         * @var AbstractFlow $flow
         */
        $flow = app($flow);
        
        $flow->setUser($this->user);
        $flow->setMessage($this->message);
        $flow->setContext($this->context);
        
        return $flow;
    }
    
    
    abstract protected function first();
}