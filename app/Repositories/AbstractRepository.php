<?php

namespace App\Repositories;

//use Eloquent;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class AbstractRepository
{
   /**
     * @var Eloquent |User|Message
     */
    protected $entity;
    
    public function __construct(Eloquent $entity)
    {
        $this->entity = $entity;
    }
}