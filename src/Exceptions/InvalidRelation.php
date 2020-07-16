<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Exceptions;

use Exception;

class InvalidRelation extends Exception
{
    public $message = 'Package allows only following relations : BelongsToJoin, HasOneJoin, HasMany, MorphOne and MorphMany.';
}
