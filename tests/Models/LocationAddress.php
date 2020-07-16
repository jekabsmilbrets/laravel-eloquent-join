<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LocationAddress
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models
 */
class LocationAddress extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'location_addresses';

    /**
     * @var string[]
     */
    protected $fillable = ['address', 'is_primary'];

    /**
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
