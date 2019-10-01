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
    public static function save(User $user, AbstractFlow $flow, string $state, array $options = [])
    {
        Log::debug('Context.save', [
            'user' => $user->toArray(),
            'flow' => get_class($flow),
            'state' => $state,
            'options' => $options,
        ]);
        
        Cache::forever(self::key($user), [
            'flow' => get_class($flow),
            'state' => $state,
            'options' => $options,
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
    
    public static function update(User $user, array $options = [])
    {
        $currentContext = self::get($user);
        
        Log::debug('Context.update', [
            'user' => $user->toArray(),
            'options' => $options,
            'current_context' => $currentContext,
        ]);
        
        Cache::forever(self::key($user), [
            'flow' => $currentContext['flow'],
            'state' => $currentContext['state'],
            'options' => $options,
        ]);
    }
    
    
    
    private static function key(User $user)
    {
        return 'context_' . $user->id;
    }
}
