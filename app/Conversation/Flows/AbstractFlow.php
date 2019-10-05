<?php

namespace App\Conversation\Flows;

use App\Conversation\Traits\InteractWithContext;
use App\Entities\User;
use App\Entities\Message;
use App\Traits\Loggable;
use App\Exceptions\ConversationException;


/**
 * Class AbstractFlow
 * 
 * @method getNextState(string $current = null)
 * @method hasTrigger(string $value)
 * 
 * @packages App\Conversation\Flows
 */
abstract class AbstractFlow
{
    use Loggable, InteractWithContext;
    
    /**
     * @var User
     */
    protected $user;
    
    /**
     * @var Message 
     */
    protected $message;
    
    
    
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    
    public function setMessage(Message $message)
    {
        $this->message = $message;
    }
    
    /**
     * Handle flow
     * 
     * @throws ConversationException
     * 
     */
    public function handle(): bool
    {
        $this->log('handle', [
            'user' => $this->user->id,
            'message' => $this->message->text,
        ]);
        
        //Если в контексте бьл указан другой flow
        $this->validate();
        
        //Search in States
        $this->log('isFlowInContext', [$this->isFlowInContext($this)]);
        
        //$this->log('test-for-states-and-triggers'. [$this->usesTriggers() ? 'true' : 'false']);
        \Log::debug(static::class . '.run', [
            'usesTriggers' => $this->usesTriggers() ? 'true' : 'false',
            'triggers' => $this->triggers,
            'usesStates' => $this->usesTriggers() ? 'true' : 'false',
            'states' => $this->states,
            'class_uses' => class_uses($this),
            //'microtime' => microtime(true),
        ]);
        
        //Если states используются и flow соответствует текущему контексту
        if($this->usesStates() && $this->isFlowInContext($this)) {
            $state = $this->getNextState($this->context()->getState());
            
            //$this->log('state-for-triggers'. ['state' => $state]);
            
            if(is_null($state)) {
                $this->clearContext();
                throw new ConversationException('Next state is not defined');
            }
            $this->runState($state);
            return true;
        }
        
        //Search in Triggers
        if($this->usesTriggers() && $this->hasTrigger($this->message->text)) {
            $state = $this->getNextState();
            
            //$this->log('state-for-triggers'. ['state' => $state]);
            
            $this->runState($state);
            return true;
        }
        return false;
    }
    
   
    
    /**
     * @param string $state
     * 
     *
     */
    public function runState(string $state)
    {
        $this->log('runState', [
            'state' => $state,
        ]);
        
        //Run target state
        $this->setContext($this, $state, $this->context()->getOptions());
        $this->$state();
        
    }
    
    protected function runFlow($flow, string $state = null)
    {
        $this->clearContext();
        
        /**
         * @var AbstractFlow $flow
         */
        $flow = app($flow);
        $flow->setUser($this->user);
        $flow->setMessage($this->message);
        
        $state = $state ?? $flow->getNextState();
        $flow->runState($state);
    }
    
    //Проверка на соответствие flow контексту
    private function validate()
    {
        //Context has another flow
        if(
            $this->context()->hasFlow() &&
            get_class($this->context()->getFlow()) !== get_class($this)
        )
        {
            throw new ConversationException('Context has another flow');
        }
    }
    
    //Првоерка, использует ли flow states
    private function usesStates(): bool
    {
        return in_array(\App\Conversation\Traits\HasStates::class, class_uses($this));
    }
    
    //Првоерка, использует ли flow triggers
    private function usesTriggers(): bool
    {
        return in_array(\App\Conversation\Traits\HasTriggers::class, class_uses($this));
    }
    
    
    
}