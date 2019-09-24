<?php

Route::post('/telegram/'. config('services.telegram.token'), 
        ['as' => 'telegram.webhook', 'uses' => 'TelegramController@process']);