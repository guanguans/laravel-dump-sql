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

    class Eloquent extends \Illuminate\Database\Eloquent\Model
    {
        /**
         * @see \Guanguans\LaravelDumpSql\ServiceProvider::registerEloquentBuilderMacro()
         * @static
         */
        public function toRawSql()
        {
            return \Illuminate\Database\Eloquent\Builder::toRawSql();
        }

        /**
         * @see \Guanguans\LaravelDumpSql\ServiceProvider::registerEloquentBuilderMacro()
         * @static
         */
        public function dumpSql()
        {
            return \Illuminate\Database\Eloquent\Builder::dumpSql();
        }

        /**
         * @see \Guanguans\LaravelDumpSql\ServiceProvider::registerEloquentBuilderMacro()
         * @static
         */
        public function ddSql()
        {
            return \Illuminate\Database\Eloquent\Builder::ddSql();
        }
    }
}

namespace Illuminate\Database\Query {
    class Builder
    {
        /**
         * @see \Guanguans\LaravelDumpSql\ServiceProvider::boot()
         * @static
         */
        public function toRawSql()
        {
            return \Illuminate\Database\Query\Builder::toRawSql();
        }

        /**
         * @see \Guanguans\LaravelDumpSql\ServiceProvider::boot()
         * @static
         */
        public function dumpSql()
        {
            return \Illuminate\Database\Query\Builder::dumpSql();
        }

        /**
         * @see \Guanguans\LaravelDumpSql\ServiceProvider::boot()
         * @static
         */
        public function ddSql()
        {
            return \Illuminate\Database\Query\Builder::ddSql();
        }
    }
}
