<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Location
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models
 */
class Location extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'locations';

    /**
     * @var string[]
     */
    protected $fillable = ['address', 'seller_id', 'is_primary', 'is_secondary', 'city_id'];

    /**
     * @return BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * @return BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return HasOne
     */
    public function locationAddressPrimary()
    {
        return $this->hasOne(LocationAddress::class)
            ->where('is_primary', '=', 1);
    }

    /**
     * @return HasMany
     */
    public function integrations()
    {
        return $this->hasMany(Integration::class);
    }

    /**
     * @return MorphMany
     */
    public function tags()
    {
        return $this->morphMany(Tag::class, 'taggable');
    }
}
