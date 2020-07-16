<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Exceptions;

use Exception;

/**
 * Class InvalidRelationGlobalScope
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Exceptions
 */
class InvalidRelationGlobalScope extends Exception
{
    /**
     * @var string
     */
    public $message = 'Package allows only SoftDeletingScope global scope.';
}
