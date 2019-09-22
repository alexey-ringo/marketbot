<?php

namespace Test\Unit\Services;

use Tests\TestCase;
use App\Services\CategoryService;
use Schema\Collection;

/**
 * @property CategoryService service
 */
class CategoryServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        
        //$this->service = new CategoryService(app(\Schema\Client::class));
        $this->service = new CategoryService($this->createApplication()->make(\Schema\Client::class));
    }
    
    public function test_all()
    {
        $result = $this->service->all();
        //dd($result);
        $this->assertInstanceOf(Collection::class, $result);
    }
}