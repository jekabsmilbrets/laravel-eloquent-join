<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Exceptions;

use Exception;

class InvalidRelationClause extends Exception
{
    public $message = 'Package allows only following clauses on relation : where, orWhere, withTrashed, onlyTrashed and withoutTrashed.';
}
