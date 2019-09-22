<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Schema\Client;

class SchemaIOServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, function() {
            $config = config('services.schema_io');
            //dd($config);
            
            return new Client($config['id'], $config['secret']);
        });
            
        
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
