<?php

/*
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql;

use Guanguans\LaravelDumpSql\Handlers\ListenedSqlHandler;
use Guanguans\LaravelDumpSql\Traits\RegisterDatabaseBuilderMethodAble;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\Facades\App;
use Laravel\Lumen\Application as LumenApplication;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    use RegisterDatabaseBuilderMethodAble;

    /**
     * Perform post-registration booting of services.
     *
     * @throws \InvalidArgumentException
     */
    public function boot()
    {
        // $this->setupConfig();

        /*
         * Register the `toRawSql` macro.
         */
        $this->registerDatabaseBuilderMethod('toRawSql', function () {
            return array_reduce($this->getBindings(), function ($sql, $binding) {
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'", $sql, 1);
            }, $this->toSql());
        });

        /*
         * Register the `dumpSql` macro.
         */
        $this->registerDatabaseBuilderMethod('dumpSql', function () {
            dump($this->toRawSql());
        });

        /*
         * Register the `ddSql` macro.
         */
        $this->registerDatabaseBuilderMethod('ddSql', function () {
            dd($this->toRawSql());
        });

        /*
         * Register the `listenedSql` macro.
         */
        $this->registerDatabaseBuilderMethod('listenedSql', function ($target) {
            return tap($this, function ($queryBuilder) use ($target) {
                call_user_func(App::make(ListenedSqlHandler::class), $target);
            });
        });

        /*
         * Register the `logListenedSql` macro.
         */
        $this->registerDatabaseBuilderMethod('logListenedSql', function () {
            return $this->listenedSql('log');
        });

        /*
         * Register the `dumpListenedSql` macro.
         */
        $this->registerDatabaseBuilderMethod('dumpListenedSql', function () {
            return $this->listenedSql('dump');
        });

        /*
         * Register the `ddListenedSql` macro.
         */
        $this->registerDatabaseBuilderMethod('ddListenedSql', function () {
            return $this->listenedSql('dd');
        });
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath($raw = __DIR__.'/../config/dumpsql.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('dumpsql.php')], 'laravel-dump-sql');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('dumpsql');

            $this->app->bindIf(ConnectionInterface::class, function ($app) {
                return $app['db']->connection();
            });
        }

        $this->mergeConfigFrom($source, 'dumpsql');
    }
}
