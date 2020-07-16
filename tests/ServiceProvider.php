<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations/');
    }
}
