<?php

namespace App\Conversation;

use App\Conversation\Flows\AbstractFlow;
use App\Entities\User;
use Cache;
//use Redis;
//use Illuminate\Support\Facades\Redis;
use Log;

class Context
{
    public static function save(User $user, AbstractFlow $flow, string $state)
    {
        Log::debug('Context.save', [
            'user' => $user->toArray(),
            'flow' => get_class($flow),
            'state' => $state,
        ]);
        
        Cache::forever(self::key($user), [
            'flow' => get_class($flow),
            'state' => $state,
        ]);
    }
    
    /**
     * @paran User $user
     * @return array
     */
    public static function get(User $user): array
    {
        return Cache::get(self::key($user), []);
    }
    
    private static function key(User $user)
    {
        return 'context_' . $user->id;
    }
}
