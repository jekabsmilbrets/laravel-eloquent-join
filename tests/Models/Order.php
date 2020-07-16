<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models
 */
class Order extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'orders';

    /**
     * @var string[]
     */
    protected $fillable = ['number', 'seller_id'];

    /**
     * @return BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
