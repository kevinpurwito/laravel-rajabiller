<?php

namespace Kevinpurwito\LaravelRajabiller;

use Illuminate\Support\Facades\Facade;

class RajabillerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Rajabiller';
    }
}
