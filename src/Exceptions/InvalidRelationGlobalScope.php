<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Exceptions;

use Exception;

class InvalidRelationGlobalScope extends Exception
{
    public $message = 'Package allows only SoftDeletingScope global scope.';
}
