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
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
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

        /*
         * Register the `listenSql` macro.
         */
        $this->registerDatabaseBuilderMethod('listenSql', function ($target = 'dd', $isOutputedToLogging = true) {
            if (! in_array($target, [null, 'dump', 'dd'])) {
                throw new InvalidArgumentException('Invalid target argument.');
            }

            DB::listen(function (QueryExecuted $query) use ($target, $isOutputedToLogging) {
                $sqlWithPlaceholders = str_replace(['%', '?', '%s%s'], ['%%', '%s', '?'], $query->sql);

                $bindings = $query->connection->prepareBindings($query->bindings);
                $pdo = $query->connection->getPdo();
                $realSql = $sqlWithPlaceholders;

                $seconds = $query->time / 1000;
                if ($seconds < 0.001) {
                    $duration = round($seconds * 1000000).'μs';
                } elseif ($seconds < 1) {
                    $duration = round($seconds * 1000, 2).'ms';
                } else {
                    $duration = round($seconds, 2).'s';
                }

                if (count($bindings) > 0) {
                    $realSql = vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings));
                }

                $sqlInformation = sprintf(
                    '[%s] [%s] %s | %s: %s',
                    $query->connection->getDatabaseName(),
                    $duration,
                    $realSql,
                    request()->method(),
                    request()->getRequestUri()
                );

                switch ($target) {
                    case null:
                        break;
                    case 'dump':
                        dump($sqlInformation);
                        break;
                    case 'dd':
                        dd($sqlInformation);
                        break;
                }

                $isOutputedToLogging and Log::channel(config('logging.default'))->debug($sqlInformation);
            });

            return $this;
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
