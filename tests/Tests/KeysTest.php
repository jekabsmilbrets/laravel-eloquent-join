<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Tests;

use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Key\Order;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Key\Seller;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\TestCase;

/**
 * Class KeysTest
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Tests
 */
class KeysTest extends TestCase
{
    /**
     *
     */
    public function testBelongsTo()
    {
        Order::joinRelations('seller')
            ->get();

        $queryTest = 'select key_orders.* 
            from "key_orders" 
            left join "key_sellers" on "key_sellers"."id_seller_primary" = "key_orders"."id_seller_foreign" 
            group by "key_orders"."id_order_primary"';

        $this->assertQueryMatches($queryTest, $this->fetchQuery());
    }

    /**
     *
     */
    public function testHasOne()
    {
        Seller::joinRelations('location')
            ->get();

        $queryTest = 'select key_sellers.*
            from "key_sellers"
            left join "key_locations" on "key_locations"."id_seller_foreign" = "key_sellers"."id_seller_primary"
            group by "key_sellers"."id_seller_primary"';

        $this->assertQueryMatches($queryTest, $this->fetchQuery());
    }

    /**
     *
     */
    public function testHasMany()
    {
        Seller::joinRelations('locations')
            ->get();

        $queryTest = 'select key_sellers.*
            from "key_sellers"
            left join "key_locations" on "key_locations"."id_seller_foreign" = "key_sellers"."id_seller_primary"
            group by "key_sellers"."id_seller_primary"';

        $this->assertQueryMatches($queryTest, $this->fetchQuery());
    }
}
