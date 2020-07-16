<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Tests\Clauses;

use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Order;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\TestCase;

/**
 * Class WhereNotInTest
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Tests\Clauses
 */
class WhereNotInTest extends TestCase
{
    /**
     *
     */
    public function testWhere()
    {
        Order::joinRelations('seller')
            ->whereNotInJoin('seller.id', [1, 2])
            ->get();

        $queryTest = 'select orders.* 
            from "orders" 
            left join "sellers" on "sellers"."id" = "orders"."seller_id" 
            where "sellers"."id" not in (?, ?)
            and "orders"."deleted_at" is null 
            group by "orders"."id"';

        $this->assertQueryMatches($queryTest, $this->fetchQuery());
    }
}
