<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use JekabsMilbrets\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use EloquentJoin;
}
