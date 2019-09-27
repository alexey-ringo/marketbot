<?php

namespace App\Events\Conversation;

use App\Conversation\Flows\AbstractFlow;
use App\Entities\User;

//---------------------------------
//use Illuminate\Broadcasting\Channel;
//---------------------------------

use Illuminate\Queue\SerializesModels;

//----------------------------------------
//use Illuminate\Broadcasting\PrivateChannel;
//use Illuminate\Broadcasting\PresenceChannel;
//----------------------------------------

use Illuminate\Foundation\Events\Dispatchable;

//-----------------------------------------------
//use Illuminate\Broadcasting\InteractsWithSockets;
//use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
//-----------------------------------------------

class onFlowRunned
{
    use Dispatchable, /*InteractsWithSockets, */SerializesModels;
    
    protected $user;
    protected $flow;
    protected $state;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, AbstractFlow $flow, string $state)
    {
        $this->user = $user;
        $this->flow = $flow;
        $this->state = $state;
    }
    
    public function getUser(): User
    {
        return $this->user;
    }
    
    public function getFlow(): AbstractFlow
    {
        return $this->flow;
    }
    
    public function getState(): string
    {
        return $this->state;
    }
    

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    //public function broadcastOn()
    //{
    //    return new PrivateChannel('channel-name');
    //}
}
