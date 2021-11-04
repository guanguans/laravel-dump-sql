<?php

/*
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql\Handlers;

use Guanguans\LaravelDumpSql\Traits\FetchesStackTrace;
use Illuminate\Container\Container;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ListenedSqlHandler
{
    use FetchesStackTrace;

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
        if (! in_array($target, ['log', 'dump', 'dd'])) {
            throw new InvalidArgumentException('Invalid target argument.');
        }

        DB::listen(function (QueryExecuted $queryExecutedEvent) use ($target) {
            $stackTrace = $this->getCallerFromStackTrace();

            $formatSql = $this->formatSqlInfo([
                'connection' => $queryExecutedEvent->connectionName,
                'file' => $stackTrace['file'],
                'line' => $stackTrace['line'],
                'time' => $this->formatQueryExecutedTime($queryExecutedEvent->time / 1000),
                'sql' => $this->getRealSql($queryExecutedEvent),
            ]);

            switch ($target) {
                case 'log':
                    Log::channel($this->app['config']->get('logging.default'))->debug($formatSql);
                    break;
                case 'dump':
                    dump($formatSql);
                    break;
                case 'dd':
                    dd($formatSql);
                    break;
            }
        });
    }

    /**
     * @return string
     */
    protected function getRealSql(QueryExecuted $queryExecutedEvent)
    {
        $sqlWithPlaceholders = str_replace(['%', '?', '%s%s'], ['%%', '%s', '?'], $queryExecutedEvent->sql);

        $bindings = $queryExecutedEvent->connection->prepareBindings($queryExecutedEvent->bindings);
        if (0 === count($bindings)) {
            return $sqlWithPlaceholders;
        }

        $pdo = $queryExecutedEvent->connection->getPdo();

        return vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings));
    }

    /**
     * @param $seconds
     *
     * @return string
     */
    protected function formatQueryExecutedTime($seconds)
    {
        if ($seconds < 0.001) {
            return round($seconds * 1000000).'μs';
        }

        if ($seconds < 1) {
            return round($seconds * 1000, 2).'ms';
        }

        return round($seconds, 2).'s';
    }

    /**
     * @return string
     */
    protected function formatSqlInfo(array $sqlInfo)
    {
        $formatSql = array_reduces($sqlInfo, function ($carry, $val, $name) {
            return $carry.sprintf('[%s: %s] ', $name, $val);
        }, '');

        return trim($formatSql);
    }
}
