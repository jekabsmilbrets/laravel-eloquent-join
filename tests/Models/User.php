<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends BaseModel
{
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = ['name'];

    /**
     * @return MorphOne
     */
    public function tags()
    {
        return $this->morphOne(Tag::class, 'taggable');
    }
}
