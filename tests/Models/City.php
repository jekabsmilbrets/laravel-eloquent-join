<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class City
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models
 */
class City extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'cities';

    /**
     * @var string[]
     */
    protected $fillable = ['name'];

    /**
     * @return BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * @return HasOne
     */
    public function zipCodePrimary()
    {
        return $this->hasOne(ZipCode::class)
            ->where('is_primary', '=', 1);
    }

    /**
     * @return BelongsToMany
     */
    public function sellers()
    {
        return $this->belongsToMany(Seller::class, 'locations', 'city_id', 'seller_id');
    }

    /**
     * @return HasMany
     */
    public function zipCodes()
    {
        return $this->hasMany(ZipCode::class);
    }
}
