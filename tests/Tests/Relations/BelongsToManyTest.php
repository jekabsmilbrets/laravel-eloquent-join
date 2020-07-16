<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Tests\Relations;

use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\City;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\TestCase;

class BelongsToManyTest extends TestCase
{
    public function testBelongsToMany()
    {
        City::whereJoin('sellers.id', '=', 'test')->get();

        $queryTest = 'select cities.* 
            from "cities" 
            left join "locations" as "sellers_pivot" on "sellers_pivot"."city_id" = "cities"."id" 
            left join "sellers" on "sellers"."id" = "sellers_pivot"."seller_id" 
            where "sellers"."id" = ? and "cities"."deleted_at" is null
            group by "cities"."id"';

        $this->assertQueryMatches($queryTest, $this->fetchQuery());
    }
}
