<?php

/**
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql;

use Guanguans\LaravelDumpSql\Commands\DumpSqlServerCommand;
use Guanguans\LaravelDumpSql\Handlers\ListenedSqlHandler;
use Guanguans\LaravelDumpSql\Handlers\SetVarDumperHandler;
use Guanguans\LaravelDumpSql\Macros\QueryBuilderMacro;
use Guanguans\LaravelDumpSql\Traits\RegisterDatabaseBuilderMethodAble;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation as RelationBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    use RegisterDatabaseBuilderMethodAble;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->setupConfig();

        QueryBuilder::mixin($queryBuilderMacro = $this->app->make(QueryBuilderMacro::class));
        EloquentBuilder::mixin($queryBuilderMacro);
        RelationBuilder::mixin($queryBuilderMacro);

        $this->app->singleton(ListenedSqlHandler::class);
        $this->commands(DumpSqlServerCommand::class);
        call_user_func($this->app->make(SetVarDumperHandler::class));
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath($raw = __DIR__.'/../config/dump-sql.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('dump-sql.php')], 'laravel-dump-sql');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('dump-sql');

            $this->app->bindIf(ConnectionInterface::class, function ($app) {
                return $app['db']->connection();
            });
        }

        $this->mergeConfigFrom($source, 'dump-sql');
    }
}
