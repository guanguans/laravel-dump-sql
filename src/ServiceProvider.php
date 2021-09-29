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
use Guanguans\LaravelDumpSql\Traits\RegisterDatabaseBuilderMethodAble;

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
        $this->setupConfig();

        /*
         * Register the `toRawSql` macro.
         */
        $this->registerDatabaseBuilderMethod(config('dumpsql.to_raw_sql', 'toRawSql'), function ($format = false) {
            $sql = array_reduce($this->getBindings(), function ($sql, $binding) {
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'", $sql, 1);
            }, $this->toSql());

            $format and $sql = (new SqlFormatter(new NullHighlighter()))->format($sql);

            return $sql;
        });

        /*
         * Register the `dumpSql` macro.
         */
        $this->registerDatabaseBuilderMethod(config('dumpsql.dump_sql', 'dumpSql'), function ($format = false) {
            dump($this->{config('dumpsql.to_raw_sql', 'toRawSql')}($format));
        });

        /*
         * Register the `ddSql` macro.
         */
        $this->registerDatabaseBuilderMethod(config('dumpsql.dd_sql', 'ddSql'), function ($format = false) {
            dd($this->{config('dumpsql.to_raw_sql', 'toRawSql')}($format));
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
