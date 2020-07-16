<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Seller
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models
 */
class Seller extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'sellers';

    /**
     * @var string[]
     */
    protected $fillable = ['title', 'deleted_at'];

    /**
     * @return HasOne
     */
    public function location()
    {
        return $this->hasOne(Location::class)
            ->where('is_primary', '=', 0)
            ->where('is_secondary', '=', 0);
    }

    /**
     * @return HasMany
     */
    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    /**
     * @return HasOne
     */
    public function locationPrimary()
    {
        return $this->hasOne(Location::class)
            ->where('is_primary', '=', 1);
    }

    /**
     * @return HasOne
     */
    public function locationPrimaryInvalid()
    {
        return $this->hasOne(Location::class)
            ->where('is_primary', '=', 1)
            ->orderBy('is_primary');
    }

    /**
     * @return HasOne
     */
    public function locationPrimaryInvalid2()
    {
        return $this->hasOne(Location::class)
            ->where(
                function ($query) {
                    return $query->where(['id' => 1]);
                }
            );
    }

    /**
     * @return HasOne
     */
    public function locationPrimaryInvalid3()
    {
        return $this->hasOne(LocationWithGlobalScope::class);
    }

    /**
     * @return HasOne
     */
    public function locationSecondary()
    {
        return $this->hasOne(Location::class)
            ->where('is_secondary', '=', 1);
    }

    /**
     * @return HasOne
     */
    public function locationPrimaryOrSecondary()
    {
        return $this->hasOne(Location::class)
            ->where('is_primary', '=', 1)
            ->orWhere('is_secondary', '=', 1);
    }

    /**
     * @return BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
