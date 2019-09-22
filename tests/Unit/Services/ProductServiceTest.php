<?php

namespace Test\Unit\Services;

use Tests\TestCase;
use App\Services\ProductService;
use Schema\Collection;

/**
 * @property CategoryService service
 */
class ProductServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        
        //$this->service = new CategoryService(app(\Schema\Client::class));
        $this->service = new ProductService($this->createApplication()->make(\Schema\Client::class));
    }
    
    public function test_all()
    {
        $result = $this->service->all();
        //dd($result);
        $this->assertInstanceOf(Collection::class, $result);
    }
}