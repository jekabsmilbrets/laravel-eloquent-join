<?php

namespace JekabsMilbrets\Laravel\EloquentJoin\Tests\Tests\Relations;

use JekabsMilbrets\Laravel\EloquentJoin\Tests\Models\User;
use JekabsMilbrets\Laravel\EloquentJoin\Tests\TestCase;

/**
 * Class MorphOneTest
 *
 * @package JekabsMilbrets\Laravel\EloquentJoin\Tests\Tests\Relations
 */
class MorphOneTest extends TestCase
{
    /**
     *
     */
    public function testMorphOne()
    {
        $sql = User::joinRelations('tags')->toSql();

        $queryTest = 'select users.* from `users` left join `tags` on `tags`.`taggable_id` = `users`.`id` and `tags`.`taggable_type` = ? where `users`.`deleted_at` is null group by `users`.`id`';

        $this->assertEquals($queryTest, $sql);
    }
}
