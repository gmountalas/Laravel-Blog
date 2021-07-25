<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static int increment(string $key, array $tags = null)
 */
class CounterFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'App\Contracts\CounterContract';
    }
}