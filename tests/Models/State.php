<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class State extends BaseModel
{
    use SoftDeletes;

    protected $table = 'states';

    protected $fillable = ['name'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
