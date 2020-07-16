<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Key;

use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\BaseModel;

/**
 * Class Location
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Key
 */
class Location extends BaseModel
{
    /**
     * @var string
     */
    protected $primaryKey = 'id_location_primary';

    /**
     * @var string
     */
    protected $table = 'key_locations';

    /**
     * @var string[]
     */
    protected $fillable = ['address', 'id_seller_foreign'];
}
