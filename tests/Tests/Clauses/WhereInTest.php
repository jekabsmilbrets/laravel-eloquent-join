<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Tests\Clauses;

use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Order;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\TestCase;

/**
 * Class WhereInTest
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Tests\Clauses
 */
class WhereInTest extends TestCase
{
    /**
     *
     */
    public function testWhere()
    {
        Order::joinRelations('seller')
            ->whereInJoin('seller.id', [1, 2])
            ->get();

        $queryTest = 'select orders.* 
            from "orders" 
            left join "sellers" on "sellers"."id" = "orders"."seller_id" 
            where "sellers"."id" in (?, ?)
            and "orders"."deleted_at" is null 
            group by "orders"."id"';

        $this->assertQueryMatches($queryTest, $this->fetchQuery());
    }
}
