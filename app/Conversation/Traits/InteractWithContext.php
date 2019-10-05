<?php

namespace App\Conversation\Traits;

use App\Conversation\Context;
use App\Conversation\Flows\AbstractFlow;
use App\Entities\User;
use Cache;

trait InteractWithContext
{
    /**
     * @var User
     */
    protected $user;
    
    protected function setContext(AbstractFlow $flow, string $state, array $options = [])
    {
        $value = new Context($flow, $state, $options);
        $this->save($value);
    }
    
    protected function clearContext()
    {
        Cache::forget($this->key());
    }
    
    //Сохранить в контексте какое либо значение для options
    protected function remember(string $key, string $value)
    {
        $context = $this->context();
        $context->setOption($key, $value);
        
        $this->save($context);
    }
    
    //Удалить какой либо option из контекста
    protected function forget(string $key)
    {
        $context = $this->context();
        $context->removeOption($key);
        
        $this->save($context);
    }
    
    //Проверка на то, что flow, обрабатываемый в AbstractFlow, находится в контексте
    protected function isFlowInContext(AbstractFlow $flow): bool
    {
        return get_class($this->context()->getFlow()) == get_class($flow);
    }
    
    protected function context(): Context
    {
        return Cache::get($this->key(), new Context());
    }
    
    private function save($context)
    {
        \Log::debug(static::class . '.save', [
            'context' => $context->getOptions(),
        ]);
        Cache::forever($this->key(), $context);
    }
    
    private function key(): string
    {
        return 'context_' . $this->user->id;
    }
}