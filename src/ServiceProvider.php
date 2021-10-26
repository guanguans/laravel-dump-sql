<?php

/*
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql;

use Doctrine\SqlFormatter\NullHighlighter;
use Doctrine\SqlFormatter\SqlFormatter;
use Guanguans\LaravelDumpSql\Handlers\ListenedSqlHandler;
use Guanguans\LaravelDumpSql\Traits\RegisterDatabaseBuilderMethodAble;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Foundation\Application as LaravelApplication;
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
        $this->registerDatabaseBuilderMethod('toRawSql', function ($format = false) {
            $sql = array_reduce($this->getBindings(), function ($sql, $binding) {
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'", $sql, 1);
            }, $this->toSql());

            $format and $sql = (new SqlFormatter(new NullHighlighter()))->format($sql);

            return $sql;
        });

        /*
         * Register the `dumpSql` macro.
         */
        $this->registerDatabaseBuilderMethod('dumpSql', function ($format = false) {
            dump($this->toRawSql($format));
        });

        /*
         * Register the `ddSql` macro.
         */
        $this->registerDatabaseBuilderMethod('ddSql', function ($format = false) {
            dd($this->toRawSql($format));
        });

        /*
         * Register the `listenedSql` macro.
         */
        $this->registerDatabaseBuilderMethod('listenedSql', function ($target) {
            return tap($this, function ($queryBuilder) use ($target) {
                app()->call(ListenedSqlHandler::class, [
                    'target' => $target,
                ]);
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
