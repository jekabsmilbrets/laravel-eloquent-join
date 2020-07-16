<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Exceptions;

use Exception;

class InvalidAggregateMethod extends Exception
{
    public $message = 'Invalid aggregate method';
}
