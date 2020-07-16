<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class User
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models
 */
class User extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var string[]
     */
    protected $fillable = ['name'];

    /**
     * @return MorphOne
     */
    public function tags()
    {
        return $this->morphOne(Tag::class, 'taggable');
    }
}
