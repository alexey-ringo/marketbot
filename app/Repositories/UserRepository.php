<?php

namespace App\Repositories;

use App\Entities\User;

/**
 * Class UserRepository
 * 
 * $property
 * 
 * $package App\Repository
 */
class UserRepository extends AbstractRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
    
    //Не используем
    public function findByChatId(int $value): User
    {
        return $this->entity->where('chat_id', $value)->firstOrFail();
    }
    
    public function store(int $id, string $firstName, string $lastName, string $username): User
    {
        $values = [
            'chat_id' => $id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
        ];
        
        return $this->entity->firstOrCreate(['chat_id' => $id], $values);    
    }
}