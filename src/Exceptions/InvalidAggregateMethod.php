<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Exceptions;

use Exception;

/**
 * Class InvalidAggregateMethod
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Exceptions
 */
class InvalidAggregateMethod extends Exception
{
    /**
     * @var string
     */
    public $message = 'Invalid aggregate method';
}
