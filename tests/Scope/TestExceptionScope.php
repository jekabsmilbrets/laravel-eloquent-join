<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Scope;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Class TestExceptionScope
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Scope
 */
class TestExceptionScope implements Scope
{
    /**
     * @param Builder $builder
     * @param Model   $model
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('test', '=', 'test');
    }
}
