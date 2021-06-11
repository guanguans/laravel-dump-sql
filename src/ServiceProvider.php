<?php

/*
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql;

use Guanguans\LaravelDumpSql\Traits\RegisterDatabaseBuilderMethodAble;
use InvalidArgumentException;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    use RegisterDatabaseBuilderMethodAble;

    /**
     * Perform post-registration booting of services.
     *
     * @throws InvalidArgumentException
     */
    public function boot()
    {
        $this->setupConfig();

        /*
         * Register the `toRawSql` macro.
         */
        $this->registerDatabaseBuilderMethod(config('dumpsql.to_raw_sql', 'toRawSql'), function () {
            return array_reduce($this->getBindings(), function ($sql, $binding) {
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'", $sql, 1);
            }, $this->toSql());
        });

        /*
         * Register the `dumpSql` macro.
         */
        $this->registerDatabaseBuilderMethod(config('dumpsql.dump_sql', 'dumpSql'), function () {
            dump($this->{config('dumpsql.to_raw_sql', 'toRawSql')}());
        });

        /*
         * Register the `ddSql` macro.
         */
        $this->registerDatabaseBuilderMethod(config('dumpsql.dd_sql', 'ddSql'), function () {
            dd($this->{config('dumpsql.to_raw_sql', 'toRawSql')}());
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
}
