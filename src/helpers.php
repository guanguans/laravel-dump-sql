<?php

/**
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

use Guanguans\LaravelDumpSql\Handlers\ListenedSqlHandler;

if (! function_exists('array_reduces')) {
    /**
     * @param null $carry
     *
     * @return mixed|null
     */
    function array_reduces(array $array, callable $callback, $carry = null)
    {
        foreach ($array as $key => $value) {
            $carry = call_user_func($callback, $carry, $value, $key);
        }

        return $carry;
    }
}

if (! function_exists('enable_listen_sql')) {
    /**
     * @param $target
     *
     * @return mixed
     */
    function enable_listen_sql($target)
    {
        return call_user_func(
            tap(app(ListenedSqlHandler::class), function (ListenedSqlHandler $listenedSqlHandler) {
                $listenedSqlHandler->enable();
            }),
            $target
        );
    }
}

if (! function_exists('enable_log_listened_sql')) {
    /**
     * @return mixed
     */
    function enable_log_listened_sql()
    {
        return enable_listen_sql('log');
    }
}

if (! function_exists('enable_dump_listened_sql')) {
    /**
     * @return mixed
     */
    function enable_dump_listened_sql()
    {
        return enable_listen_sql('dump');
    }
}

if (! function_exists('enable_dd_listened_sql')) {
    /**
     * @return mixed
     */
    function enable_dd_listened_sql()
    {
        return enable_listen_sql('dd');
    }
}

if (! function_exists('disable_listened_sql')) {
    function disable_listened_sql()
    {
        app(ListenedSqlHandler::class)->disable();
    }
}
