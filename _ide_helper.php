<?php

/*
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace {
    class DB extends \Illuminate\Support\Facades\DB
    {
    }
}

namespace Illuminate\Database\Query {
    /**
     * @method string toRawSql()
     * @method void   dumpSql()
     * @method void   ddSql()
     * @method $this  listenedSql(string $target)
     * @method $this  logListenedSql()
     * @method $this  dumpListenedSql()
     * @method $this  ddListenedSql()
     *
     * @see \Guanguans\LaravelDumpSql\ServiceProvider
     * @see \Illuminate\Database\Query\Builder
     */
    class Builder
    {
    }
}

namespace Illuminate\Database\Eloquent {
    /**
     * @method string toRawSql()
     * @method void   dumpSql()
     * @method void   ddSql()
     * @method $this  listenedSql(string $target)
     * @method $this  logListenedSql()
     * @method $this  dumpListenedSql()
     * @method $this  ddListenedSql()
     *
     * @mixin \Illuminate\Database\Query\Builder
     *
     * @see \Guanguans\LaravelDumpSql\ServiceProvider
     * @see \Illuminate\Database\Eloquent\Builder
     */
    class Builder
    {
    }
}

namespace Illuminate\Database\Eloquent\Relations {
    /**
     * @method string toRawSql()
     * @method void   dumpSql()
     * @method void   ddSql()
     * @method $this  listenedSql(string $target)
     * @method $this  logListenedSql()
     * @method $this  dumpListenedSql()
     * @method $this  ddListenedSql()
     *
     * @mixin \Illuminate\Database\Eloquent\Builder
     *
     * @see \Guanguans\LaravelDumpSql\ServiceProvider
     * @see \Illuminate\Database\Eloquent\Relations\Relation
     */
    class Relation
    {
    }
}
