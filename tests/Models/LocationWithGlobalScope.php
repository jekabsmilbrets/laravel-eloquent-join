<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\Scope\TestExceptionScope;

class LocationWithGlobalScope extends BaseModel
{
    use SoftDeletes;

    protected $table = 'locations';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new TestExceptionScope());
    }
}
