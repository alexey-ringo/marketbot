<?php

namespace App\Entities;

use Illuminate\Notifications\Notifiable;

/**
 * @property
 * 
 * @method static User firstOrCreate()
 */
 

class User extends AbstractEntity
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chat_id', 
        'first_name', 
        'last_name',
        'user_name'
    ];
}
