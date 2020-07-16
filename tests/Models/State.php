<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class State
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models
 */
class State extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'states';

    /**
     * @var string[]
     */
    protected $fillable = ['name'];

    /**
     * @return HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
