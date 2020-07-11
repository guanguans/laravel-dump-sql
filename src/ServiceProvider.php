<?php

/*
 * This file is part of the guanguans/laravel-raw-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelRawSql;

use Closure;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Guanguans\LaravelRawSql\Exceptions\InvalidArgumentException;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        /**
         * Register the `toRawSql` macro.
         */
        $this->registerBuilderMacro('toRawSql', function ($macro) {
            QueryBuilder::macro($macro, function () {
                return array_reduce($this->getBindings(), function ($sql, $binding) {
                    return preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'", $sql, 1);
                }, $this->toSql());
            });
        });

        /**
         * Register the `dumpRawSql` macro.
         */
        $this->registerBuilderMacro('dumpRawSql', function ($macro) {
            QueryBuilder::macro($macro, function () {
                dump($this->toRawSql());
            });
        });

        /**
         * Register the `ddRawSql` macro.
         */
        $this->registerBuilderMacro('ddRawSql', function ($macro) {
            QueryBuilder::macro($macro, function () {
                dd($this->toRawSql());
            });
        });
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = __DIR__.'/../config/rawsql.php';

        if ($this->app->runningInConsole()) {
            $this->publishes([$source => config_path('rawsql.php')], 'laravel-raw-sql');
        }

        $this->mergeConfigFrom($source, 'rawsql');
    }

    /**
     * @param $macro
     * @param  \Closure  $closure
     * @return bool
     * @throws \Guanguans\LaravelRawSql\Exceptions\InvalidArgumentException
     */
    protected function registerBuilderMacro($macro, Closure $closure)
    {
        if (!is_string($macro)) {
            throw new InvalidArgumentException('Macro name must be a string');
        }

        $closure($macro);

        $this->registerEloquentBuilderMacro($macro);

        return true;
    }

    /**
     * @param $macro
     * @return bool
     * @throws \Guanguans\LaravelRawSql\Exceptions\InvalidArgumentException
     */
    protected function registerEloquentBuilderMacro($macro)
    {
        if (!is_string($macro)) {
            throw new InvalidArgumentException('Macro name must be a string');
        }

        EloquentBuilder::macro($macro, function () use ($macro) {
            return ($this->getQuery()->$macro());
        });

        return true;
    }

    /**
     * Register the provider.
     */
    public function register()
    {
    }
}
