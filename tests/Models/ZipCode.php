<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ZipCode
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models
 */
class ZipCode extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'zip_codes';

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'is_primary'];
}
