<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Exceptions;

use Exception;

/**
 * Class InvalidRelation
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Exceptions
 */
class InvalidRelation extends Exception
{
    /**
     * @var string
     */
    public $message = 'Package allows only following relations : BelongsToJoin, HasOneJoin, HasMany, MorphOne and MorphMany.';
}
