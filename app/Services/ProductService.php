<?php

namespace App\Services;

use Schema\Client;
use Schema\Collection;


class ProductService
{
    private $api;
    
    public function __construct(Client $api) 
    {
        $this->api = $api;
    }
    
    public function all(): Collection
    {
        return $this->api->get('/products');
    }
}
