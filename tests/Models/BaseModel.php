<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use JekabsMilbrets\Laravel\EloquentJoin\Traits\EloquentJoin;

class BaseModel extends Model
{
    use EloquentJoin;
}
