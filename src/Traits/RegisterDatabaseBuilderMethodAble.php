<?php

/**
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
     * @throws \InvalidArgumentException|\ReflectionException
     */
    public function registerDatabaseBuilderMethod(string $methodName, Closure $closure)
    {
        if (
            method_exists(QueryBuilder::class, $methodName) ||
            method_exists(EloquentBuilder::class, $methodName) ||
            method_exists(Relation::class, $methodName)
        ) {
            throw new InvalidArgumentException(sprintf('`%s` or `%s` or `%s` already exists method.:%s', QueryBuilder::class, EloquentBuilder::class, Relation::class, $methodName));
        }

        $parameters = array_keys(
            (new \ReflectionObject($closure))
                ->getMethod('__invoke')
                ->getParameters()
        );

        QueryBuilder::macro($methodName, $closure);

        EloquentBuilder::macro($methodName, function (...$parameters) use ($methodName) {
            return $this->getQuery()->$methodName(...$parameters);
        });

        Relation::macro($methodName, function (...$parameters) use ($methodName) {
            return $this->getQuery()->$methodName(...$parameters);
        });
    }
}
