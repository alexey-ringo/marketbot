<?php

namespace App\Conversation;

use App\Conversation\Flows\AbstractFlow;

class Context
{
    protected $flow;
    protected $state;
    protected $options;
    
    public function __construct(AbstractFlow $flow = null, string $state = null, array $options = [])
    {
        $this->flow = !is_null($flow) ?get_class($flow) : null;
        $this->state = $state;
        $this->options = $options;
    }
    
    public function hasFlow(): bool
    {
        return !is_null($this->flow);
    }
    
    /**
     * @return AbstractFlow|null
     */
    public function getFlow(): ?AbstractFlow
    {
        return $this->hasFlow() ? app($this->flow) : null;
    }
    
    public function setFlow(AbstractFlow $flow)
    {
        $this->flow = get_class($flow); 
    }
    
    public function getState()
    {
        return $this->state;
    }
    
    public function setState(string $state)
    {
        $this->state = $state;
    }
    
    public function getOptions(): array
    {
        return $this->options;
    }
    
    public function setOptions(array $options)
    {
        $this->options = $options;
    }
    
    public function setOption(string $key, string $value)
    {
        $this->options[$key] = $value;
    }
    
    public function removeOption(string $key)
    {
        if(array_key_exists($key, $this->options)) {
            unset($this->options[$key]);
        }
    }
}
