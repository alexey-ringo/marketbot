<?php

namespace App\Events\Conversation;


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

class onOptionChanged
{
    use Dispatchable, /*InteractsWithSockets, */SerializesModels;
    
    protected $user;
    protected $key;
    protected $value;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, string $key, $value)
    {
        $this->user = $user;
        $this->key = $key;
        $this->value = $value;
    }
    
    public function getUser(): User
    {
        return $this->user;
    }
    
    public function getKey(): string
    {
        return $this->key;
    }
    
    public function getValue()
    {
        return $this->value;
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
