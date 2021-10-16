<?php

/*
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use InvalidArgumentException;

trait RegisterDatabaseBuilderMethodAble
{
    /**
     * @param $methodName
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function registerDatabaseBuilderMethod($methodName, Closure $closure)
    {
        if (! is_string($methodName)) {
            throw new InvalidArgumentException('Macro name must be a string');
        }

        if (
            method_exists(QueryBuilder::class, $methodName) ||
            method_exists(EloquentBuilder::class, $methodName) ||
            method_exists(Relation::class, $methodName)
        ) {
            throw new InvalidArgumentException(sprintf('`%s` or `%s` or `%s` already exists method.:%s', QueryBuilder::class, EloquentBuilder::class, Relation::class, $methodName));
        }

        QueryBuilder::macro($methodName, $closure);

        EloquentBuilder::macro($methodName, function () use ($methodName) {
            return $this->getQuery()->$methodName();
        });

        Relation::macro($methodName, function () use ($methodName) {
            return $this->getQuery()->$methodName();
        });

        return true;
    }
}
