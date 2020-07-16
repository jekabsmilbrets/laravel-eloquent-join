<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Key;

use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\BaseModel;

class Location extends BaseModel
{
    protected $primaryKey = 'id_location_primary';

    protected $table = 'key_locations';

    protected $fillable = ['address', 'id_seller_foreign'];
}
