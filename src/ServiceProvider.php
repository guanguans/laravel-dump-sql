<?php

/*
 * This file is part of the guanguans/laravel-raw-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelRawSql;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->registerToRawSqlMacro();
        $this->registerDumpRawSqlMacro();
        $this->registerDdRawSqlMacro();
    }

    /**
     * Register the `toRawSql` macro.
     */
    protected function registerToRawSqlMacro()
    {
        QueryBuilder::macro('toRawSql', function () {
            return array_reduce($this->getBindings(), function ($sql, $binding) {
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'", $sql, 1);
            }, $this->toSql());
        });

        EloquentBuilder::macro('toRawSql', function () {
            return ($this->getQuery()->toRawSql());
        });
    }

    /**
     * Register the `dumpRawSql` macro.
     */
    protected function registerDumpRawSqlMacro()
    {
        QueryBuilder::macro('dumpRawSql', function () {
            dump($this->toRawSql());
        });

        EloquentBuilder::macro('dumpRawSql', function () {
            return ($this->getQuery()->dumpRawSql());
        });
    }

    /**
     * Register the `ddRawSql` macro.
     */
    protected function registerDdRawSqlMacro()
    {
        QueryBuilder::macro('ddRawSql', function () {
            dd($this->toRawSql());
        });

        EloquentBuilder::macro('ddRawSql', function () {
            return ($this->getQuery()->ddRawSql());
        });
    }

    /**
     * Register the provider.
     */
    public function register()
    {
    }
}
