<?php

namespace App\Repositories;

use App\Entities\Message;
use App\Entities\User;

/**
 * Class UserRepository
 * 
 * $property
 * 
 * $package App\Repository
 */
class MessageRepository extends AbstractRepository
{
    public function __construct(Message $entity)
    {
        parent::__construct($entity);
    }
    
    public function store(User $user, int $externalId, string $text): Message
    {
        return $this->entity->create([
                'user_id' => $user->id,
                'external_id' => $externalId,
                'text' => $text,
            ]);
    }
}