<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    protected function faker(): \Faker\Generator
    {
        return Faker\Factory::create();
    }
    
    protected function mock(string $class): \Mockery\MockInterface
    {
        $object = Mockery::mock($class);
        $this->app->bind($class, $object);
        
        return $object;
    }
}
