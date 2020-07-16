<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrderItem
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models
 */
class OrderItem extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'order_items';

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'order_id'];

    /**
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return mixed
     */
    public function orderWithTrashed()
    {
        return $this->belongsTo(Order::class, 'order_id')
            ->withTrashed();
    }

    /**
     * @return mixed
     */
    public function orderOnlyTrashed()
    {
        return $this->belongsTo(Order::class, 'order_id')
            ->onlyTrashed();
    }
}
