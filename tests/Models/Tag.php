<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Tag
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models
 */
class Tag extends BaseModel
{
    /**
     * @return MorphTo
     */
    public function taggable()
    {
        return $this->morphTo();
    }
}
