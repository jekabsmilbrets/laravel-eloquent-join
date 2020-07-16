<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use JekabsMilbrets\Laravel\EloquentJoin\Traits\EloquentJoin;

/**
 * Class BaseModel
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Models
 */
class BaseModel extends Model
{
    use EloquentJoin;
}
