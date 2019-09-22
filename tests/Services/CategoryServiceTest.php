<?php

namespace Test\Services;

use App\Services\CategoryService;

/**
 * @property CategoryService service
 */
class CategoryServiceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        
        $this->service - new CategoryService(app(\Schema\Client::class));
    }
    
    public function test_all()
    {
        $result = $this->service->all();
        dd($result);
        
    }
}