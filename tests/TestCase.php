<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests;

use DB;
use Dotenv\Dotenv;
use Illuminate\Foundation\Application;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Location;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Order;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\OrderItem;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Seller;

/**
 * Class TestCase.
 */
abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        if (method_exists(Dotenv::class, 'create')) {
            $dotenv = Dotenv::createImmutable(__DIR__.'/..', '.env');
        } else {
            $dotenv = new Dotenv(__DIR__.'/..', '.env');
        }

        $dotenv->load();

        parent::setUp();

        $this->artisan('migrate:fresh');

        $seller  = Seller::create(['title' => 1]);
        $seller2 = Seller::create(['title' => 2]);
        $seller3 = Seller::create(['title' => 3]);
        $seller4 = Seller::create(['title' => 4]);

        Location::create(['address' => 1, 'seller_id' => $seller->id]);
        Location::create(['address' => 2, 'seller_id' => $seller2->id]);
        Location::create(['address' => 3, 'seller_id' => $seller3->id]);
        Location::create(['address' => 3, 'seller_id' => $seller3->id]);

        Location::create(['address' => 4, 'seller_id' => $seller3->id, 'is_primary' => 1]);
        Location::create(['address' => 5, 'seller_id' => $seller3->id, 'is_secondary' => 1]);

        $order  = Order::create(['number' => '1', 'seller_id' => $seller->id]);
        $order2 = Order::create(['number' => '2', 'seller_id' => $seller2->id]);
        $order3 = Order::create(['number' => '3', 'seller_id' => $seller3->id]);

        OrderItem::create(['name' => '1', 'order_id' => $order->id]);
        OrderItem::create(['name' => '2', 'order_id' => $order2->id]);
        OrderItem::create(['name' => '3', 'order_id' => $order3->id]);

        $this->startListening();
    }

    protected function startListening()
    {
        DB::enableQueryLog();
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
    }

    /**
     * @param Application $app
     *
     * @return array|string[]
     */
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    /**
     * @return mixed
     */
    protected function fetchQuery()
    {
        $log = DB::getQueryLog();

        return end($log)['query'];
    }

    /**
     * @param $expected
     * @param $actual
     */
    protected function assertQueryMatches($expected, $actual)
    {
        $actual = preg_replace('/\s\s+/', ' ', $actual);
        $actual = str_replace(['\n', '\r'], '', $actual);

        $expected = preg_replace('/\s\s+/', ' ', $expected);
        $expected = str_replace(['\n', '\r'], '', $expected);
        $expected = '/'.$expected.'/';
        $expected = preg_quote($expected);
        $expected = str_replace(['"'], '`', $expected);

        $this->assertMatchesRegularExpression($expected, $actual);
    }
}
