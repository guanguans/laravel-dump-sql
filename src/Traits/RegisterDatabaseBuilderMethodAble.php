<?php

/**
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Relations\Relation
 */
trait RegisterDatabaseBuilderMethodAble
{
    /**
     * @throws \InvalidArgumentException|\ReflectionException
     */
    public function registerDatabaseBuilderMethod(string $methodName, \Closure $closure)
    {
        if (
            method_exists(QueryBuilder::class, $methodName)
            || method_exists(EloquentBuilder::class, $methodName)
            || method_exists(Relation::class, $methodName)
        ) {
            throw new \InvalidArgumentException(sprintf('`%s` or `%s` or `%s` already exists method.:%s', QueryBuilder::class, EloquentBuilder::class, Relation::class, $methodName));
        }

        QueryBuilder::macro($methodName, $closure);
        EloquentBuilder::macro($methodName, $closure);
        Relation::macro($methodName, $closure);
    }
}
