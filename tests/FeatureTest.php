<?php

/**
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSqlTests;

use Guanguans\LaravelDumpSqlTests\TestClasses\Models\User;
use Illuminate\Support\Facades\DB;

class FeatureTest extends TestCase
{
    public function testToRawSql()
    {
        $modelSql = User::query()->where('id', 1)->toRawSql();

        $DBSql = DB::table('users')->where('id', 1)->toRawSql();

        $this->assertEquals($modelSql, $DBSql);

        $this->assertEquals('select * from "users" where "id" = 1', $modelSql);
    }
}
