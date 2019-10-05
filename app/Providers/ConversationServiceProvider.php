<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Conversation\Conversation;

class ConversationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Conversation::class, function() {
            return new Conversation(config('conversation.flows'));
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
