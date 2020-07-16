<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Integration
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models
 */
class Integration extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'integrations';

    /**
     * @var string[]
     */
    protected $fillable = ['name'];
}
