<?php

namespace Test\Unit\Repositories;

use Tests\TestCase;

use App\Entities\Message;
use App\Entities\User;
use App\Repositories\MessageRepository;

/**
 * @property \Mockery\MockInterface user
 * 
 * @property \Mockery\MockInterface entiry
 * 
 * @property MessageRepository repository
 */
class MessageRepositoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        
        $this->user = $this->mock(User::class);
        
        $this->entity = $this->mock(Message::class);
        
        $this->repository = new MessageRepository($this->entity);
        
    }
    
    public function test_store()
    {
        $userId = $this->faker()->randomNumber();
        $this->user->shouldReceive('getAttribute')->with('id')->andReturn($userId);
        
        $externalId = $this->faker()->randomNumber();
        $text = $this->faker()->text;
        
        $this->entity->shouldReceive('create')->with([
            'user_id' => $userId,
            'external_id' => $externalId,
            'text' => $text,
        ])->andReturnSelf();
        
        $message = $this->repository->store($this->user, $externalId, $text);
        
        $this->assertSame($this->entity, $message);
    }
}