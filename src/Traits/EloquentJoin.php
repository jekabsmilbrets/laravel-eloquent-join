<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Traits;

use JekabsMilbrets\Laravel\EloquentJoin\EloquentJoinBuilder;
use JekabsMilbrets\Laravel\EloquentJoin\Exceptions\InvalidAggregateMethod;

/**
 * Trait EloquentJoin.
 */
trait EloquentJoin
{
    /**
     * @param $query
     *
     * @return EloquentJoinBuilder
     *
     * @throws InvalidAggregateMethod
     */
    public function newEloquentBuilder($query)
    {
        $newEloquentBuilder = new EloquentJoinBuilder($query);

        if (isset($this->useTableAlias)) {
            $newEloquentBuilder->setUseTableAlias($this->useTableAlias);
        }

        if (isset($this->appendRelationsCount)) {
            $newEloquentBuilder->setAppendRelationsCount($this->appendRelationsCount);
        }

        if (isset($this->leftJoin)) {
            $newEloquentBuilder->setLeftJoin($this->leftJoin);
        }

        if (isset($this->aggregateMethod)) {
            $newEloquentBuilder->setAggregateMethod($this->aggregateMethod);
        }

        return $newEloquentBuilder;
    }
}
