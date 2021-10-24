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
use Guanguans\LaravelDumpSql\Handlers\ListenSqlHandler;
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
            dump($this->{'toRawSql'}($format));
        });

        /*
         * Register the `ddSql` macro.
         */
        $this->registerDatabaseBuilderMethod('ddSql', function ($format = false) {
            dd($this->{'toRawSql'}($format));
        });

        /*
         * Register the `listenSql` macro.
         */
        $this->registerDatabaseBuilderMethod('listenSql', function ($target) {
            return tap($this, function ($queryBuilder) use ($target) {
                app()->call(ListenSqlHandler::class, [
                    'target' => $target,
                ]);
            });
        });

        /*
         * Register the `logListenSql` macro.
         */
        $this->registerDatabaseBuilderMethod('logListenSql', function () {
            return $this->listenSql('log');
        });

        /*
         * Register the `dumpListenSql` macro.
         */
        $this->registerDatabaseBuilderMethod('dumpListenSql', function () {
            return $this->listenSql('dump');
        });

        /*
         * Register the `ddListenSql` macro.
         */
        $this->registerDatabaseBuilderMethod('ddListenSql', function () {
            return $this->listenSql('dd');
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
