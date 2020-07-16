<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Exceptions;

use Exception;

/**
 * Class InvalidRelationClause
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Exceptions
 */
class InvalidRelationClause extends Exception
{
    /**
     * @var string
     */
    public $message = 'Package allows only following clauses on relation : where, orWhere, withTrashed, onlyTrashed and withoutTrashed.';
}
