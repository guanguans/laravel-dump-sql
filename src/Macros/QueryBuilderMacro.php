<?php

/**
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql\Macros;

class QueryBuilderMacro
{
    public function toRawSql()
    {
        return function () {
            /* @var \Illuminate\Database\Query\Builder $this */
            return array_reduce($this->getBindings(), function ($sql, $binding) {
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'", $sql, 1);
            }, $this->toSql());
        };
    }

    public function dumpSql()
    {
        return function () {
            /* @var \Illuminate\Database\Query\Builder $this */
            dump($this->toRawSql());
        };
    }

    public function ddSql()
    {
        return function () {
            /* @var \Illuminate\Database\Query\Builder $this */
            dd($this->toRawSql());
        };
    }

    public function listenedSql()
    {
        return function ($target) {
            /* @var \Illuminate\Database\Query\Builder $this */
            return tap($this, function ($queryBuilder) use ($target) {
                enable_listen_sql($target);
            });
        };
    }

    public function logListenedSql()
    {
        return function () {
            /* @var \Illuminate\Database\Query\Builder $this */
            return $this->listenedSql('log');
        };
    }

    public function dumpListenedSql()
    {
        return function () {
            /* @var \Illuminate\Database\Query\Builder $this */
            return $this->listenedSql('dump');
        };
    }

    public function ddListenedSql()
    {
        return function () {
            /* @var \Illuminate\Database\Query\Builder $this */
            return $this->listenedSql('dump');
        };
    }
}
