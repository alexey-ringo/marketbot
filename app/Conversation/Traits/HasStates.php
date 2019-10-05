<?php

namespace App\Conversation\Traits;

trait HasStates
{
    protected $states = [];
    
    protected function addState(string $value)
    {
        $this->states[] = $value;
        
        return $this;
    }
    
    protected function getStates(): array
    {
        return $this->states;
    }
    
    protected function getNextState($current = null): ?string
    {
        $states = $this->getStates();
        
        if(is_null($current)) {
            return $states[0];
        }
        
        $currentStateIndex = collect($this->getStates())->search(function($item) use($current) {
            return $item == $current;
        });
        
        $currentState = $states[$currentStateIndex];
        
        //Проверка на наличие следующего state
        if(isset($states[$currentStateIndex + 1])) {
            return $states[$currentStateIndex + 1];
        }
        
        return null;
    }
}