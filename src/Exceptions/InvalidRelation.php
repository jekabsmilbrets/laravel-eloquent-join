<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Exceptions;

class InvalidRelation extends \Exception
{
    public $message = 'Package allows only following relations : BelongsToJoin, HasOneJoin, HasMany, MorphOne and MorphMany.';
}
