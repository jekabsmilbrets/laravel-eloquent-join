<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Key;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\BaseModel;

/**
 * Class Seller
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Key
 */
class Seller extends BaseModel
{
    /**
     * @var string
     */
    protected $primaryKey = 'id_seller_primary';

    /**
     * @var string
     */
    protected $table = 'key_sellers';

    /**
     * @var string[]
     */
    protected $fillable = ['title'];

    /**
     * @return HasOne
     */
    public function location()
    {
        return $this->hasOne(Location::class, 'id_seller_foreign', 'id_seller_primary');
    }

    /**
     * @return HasMany
     */
    public function locations()
    {
        return $this->hasMany(Location::class, 'id_seller_foreign', 'id_seller_primary');
    }

    /**
     * @return HasOne
     */
    public function locationOwner()
    {
        return $this->hasOne(Location::class, 'id_seller_foreign', 'id_seller_owner');
    }

    /**
     * @return HasMany
     */
    public function locationsOwner()
    {
        return $this->hasMany(Location::class, 'id_seller_foreign', 'id_seller_owner');
    }
}
