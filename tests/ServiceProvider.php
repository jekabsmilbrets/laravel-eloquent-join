<?php

namespace Fico7489\Laravel\EloquentJoin\Tests;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations/');
    }
}
