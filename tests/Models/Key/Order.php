<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Key;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\BaseModel;

/**
 * Class Order
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Key
 */
class Order extends BaseModel
{
    /**
     * @var string
     */
    protected $primaryKey = 'id_order_primary';

    /**
     * @var string
     */
    protected $table = 'key_orders';

    /**
     * @var string[]
     */
    protected $fillable = ['number', 'id_seller_foreign'];

    /**
     * @return BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'id_seller_foreign', 'id_seller_primary');
    }

    /**
     * @return BelongsTo
     */
    public function sellerOwner()
    {
        return $this->belongsTo(Seller::class, 'id_seller_foreign', 'id_seller_owner');
    }
}
