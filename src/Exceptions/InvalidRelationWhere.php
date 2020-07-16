<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Exceptions;

use Exception;

/**
 * Class InvalidRelationWhere
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Exceptions
 */
class InvalidRelationWhere extends Exception
{
    /**
     * @var string
     */
    public $message = 'Package allows only following where(orWhere) clauses type on relation : ->where($column, $operator, $value) and ->where([$column => $value]).';
}
