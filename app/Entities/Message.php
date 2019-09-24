<?php

namespace App\Entities;


class Message extends AbstractEntity
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'external_id', 
        'text'
    ];
}
