<?php

/*
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql\Handlers;

use Illuminate\Container\Container;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * This file is modified from `overtrue/laravel-query-logger`.
 */
class ListenedSqlHandler
{
    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function __invoke(string $target): void
    {
        if (! in_array($target, ['log', 'dump', 'dd', 'server'])) {
            throw new InvalidArgumentException('Invalid target argument.');
        }

        DB::listen(function (QueryExecuted $query) use ($target) {
            $sqlWithPlaceholders = str_replace(['%', '?', '%s%s'], ['%%', '%s', '?'], $query->sql);

            $bindings = $query->connection->prepareBindings($query->bindings);
            $pdo = $query->connection->getPdo();
            $realSql = $sqlWithPlaceholders;
            $duration = $this->formatDuration($query->time / 1000);

            if (count($bindings) > 0) {
                $realSql = vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings));
            }

            $sql = sprintf(
                '[%s] [%s] %s | %s: %s',
                $query->connection->getDatabaseName(),
                $duration,
                $realSql,
                $this->app['request']->method(),
                $this->app['request']->getRequestUri()
            );

            switch ($target) {
                case 'log':
                    Log::channel($this->app['config']->get('logging.default'))->debug($sql);
                    break;
                case 'dump':
                    dump($sql);
                    break;
                case 'dd':
                    dd($sql);
                    break;
                case 'server':
                    dump(sprintf(
                        '[%s] [%s] %s',
                        $query->connection->getDatabaseName(),
                        $duration,
                        $realSql,
                    ));
                    break;
            }
        });
    }

    /**
     * @param float $seconds
     *
     * @return string
     */
    private function formatDuration($seconds)
    {
        if ($seconds < 0.001) {
            return round($seconds * 1000000).'μs';
        } elseif ($seconds < 1) {
            return round($seconds * 1000, 2).'ms';
        }

        return round($seconds, 2).'s';
    }
}
