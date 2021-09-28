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
     * @param $method
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function registerDatabaseBuilderMethod($method, Closure $closure)
    {
        if (! is_string($method)) {
            throw new InvalidArgumentException('Macro name must be a string');
        }

        if (method_exists(app(QueryBuilder::class), $method)) {
            throw new InvalidArgumentException(sprintf('`Illuminate\Database\Query\Builder` already exists method.:%s', $method));
        }

        QueryBuilder::macro($method, $closure);

        $this->registerEloquentBuilderMethod($method);

        return true;
    }

    /**
     * @param string $methodName
     *
     * @return bool
     */
    private function registerEloquentBuilderMethod($methodName)
    {
        EloquentBuilder::macro($methodName, function () use ($methodName) {
            return $this->getQuery()->$methodName();
        });

        return true;
    }

    /**
     * @param string $methodName
     *
     * @return bool
     */
    private function registerEloquentRelationMethod($methodName)
    {
        Relation::macro($methodName, function () use ($methodName) {
            return $this->getQuery()->$methodName();
        });

        return true;
    }
}
