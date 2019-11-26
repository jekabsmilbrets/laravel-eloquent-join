<?php

namespace Fico7489\Laravel\EloquentJoin\Tests\Tests;

use Fico7489\Laravel\EloquentJoin\Exceptions\InvalidAggregateMethod;
use Fico7489\Laravel\EloquentJoin\Exceptions\InvalidRelationClause;
use Fico7489\Laravel\EloquentJoin\Exceptions\InvalidRelationGlobalScope;
use Fico7489\Laravel\EloquentJoin\Exceptions\InvalidRelationWhere;
use Fico7489\Laravel\EloquentJoin\Tests\Models\Seller;
use Fico7489\Laravel\EloquentJoin\Tests\TestCase;

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
