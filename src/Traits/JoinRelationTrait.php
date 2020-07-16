<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Traits;

/**
 * Trait JoinRelationTrait.
 */
trait JoinRelationTrait
{
    /**
     * Handle dynamic method calls to the relationship.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        $this->getQuery()->relationClauses[] = [$method => $parameters];

        return parent::__call($method, $parameters);
    }
}
