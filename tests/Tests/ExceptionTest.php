<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Tests;

use JekabsMilbrets\Laravel\EloquentJoin\Exceptions\InvalidAggregateMethod;
use JekabsMilbrets\Laravel\EloquentJoin\Exceptions\InvalidRelationWhere;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\Seller;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\TestCase;

class ExceptionTest extends TestCase
{
    public function testInvalidRelationWhere()
    {
        try {
            Seller::whereJoin('locationPrimaryInvalid2.name', '=', 'test')->get();
        } catch (InvalidRelationWhere $e) {
            $this->assertEquals((new InvalidRelationWhere())->message, $e->getMessage());

            return;
        }

        $this->assertTrue(false);
    }

    public function testInvalidAggregateMethod()
    {
        try {
            Seller::orderByJoin('locationPrimary.id', 'asc', 'wrong')->get();
        } catch (InvalidAggregateMethod $e) {
            $this->assertEquals((new InvalidAggregateMethod())->message, $e->getMessage());

            return;
        }

        $this->assertTrue(false);
    }
}
