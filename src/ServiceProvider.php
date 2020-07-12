<?php

/*
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql;

use Closure;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Guanguans\LaravelDumpSql\Exceptions\InvalidArgumentException;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     * @throws \Guanguans\LaravelDumpSql\Exceptions\InvalidArgumentException
     */
    public function boot()
    {
        $this->setupConfig();

        /**
         * Register the `toRawSql` macro.
         */
        $this->registerBuilderMacro(config('dumpsql.to_raw_sql', 'toRawSql'), function ($macro) {
            QueryBuilder::macro($macro, function () {
                return array_reduce($this->getBindings(), function ($sql, $binding) {
                    return preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'", $sql, 1);
                }, $this->toSql());
            });
        });

        /**
         * Register the `dumpSql` macro.
         */
        $this->registerBuilderMacro(config('dumpsql.dump_sql', 'dumpSql'), function ($macro) {
            QueryBuilder::macro($macro, function () {
                dump($this->{config('dumpsql.to_raw_sql', 'toRawSql')}());
            });
        });

        /**
         * Register the `ddSql` macro.
         */
        $this->registerBuilderMacro(config('dumpsql.dd_sql', 'ddSql'), function ($macro) {
            QueryBuilder::macro($macro, function () {
                dd($this->{config('dumpsql.to_raw_sql', 'toRawSql')}());
            });
        });
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = __DIR__.'/../config/dumpsql.php';

        if ($this->app->runningInConsole()) {
            $this->publishes([$source => config_path('dumpsql.php')], 'laravel-dump-sql');
        }

        $this->mergeConfigFrom($source, 'dumpsql');
    }

    /**
     * @param $macro
     * @param  \Closure  $closure
     * @return bool
     * @throws \Guanguans\LaravelDumpSql\Exceptions\InvalidArgumentException
     */
    protected function registerBuilderMacro($macro, Closure $closure)
    {
        if (!is_string($macro)) {
            throw new InvalidArgumentException('Macro name must be a string');
        }

        if (method_exists(app(QueryBuilder::class), $macro)) {
            throw new InvalidArgumentException(sprintf('`Illuminate\Database\Query\Builder` already exists method.:%s', $macro));
        }

        $closure($macro);

        $this->registerEloquentBuilderMacro($macro);

        return true;
    }

    /**
     * @param $macro
     * @return bool
     */
    protected function registerEloquentBuilderMacro($macro)
    {
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
