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
    class Builder
    {
        /**
         * @param bool $format
         *
         * @return string
         */
        public function toRawSql($format = false)
        {
            return \Illuminate\Database\Query\Builder::toRawSql($format = false);
        }

        /**
         * @param bool $format
         *
         * @return void
         */
        public function dumpSql($format = false)
        {
            return \Illuminate\Database\Query\Builder::dumpSql($format = false);
        }

        /**
         * @param bool $format
         *
         * @return void
         */
        public function ddSql($format = false)
        {
            return \Illuminate\Database\Query\Builder::ddSql($format = false);
        }
    }
}

namespace Illuminate\Database\Eloquent {
    class Builder
    {
        /**
         * @param bool $format
         *
         * @return string
         */
        public function toRawSql($format = false)
        {
            return \Illuminate\Database\Query\Builder::toRawSql($format = false);
        }

        /**
         * @param bool $format
         *
         * @return void
         */
        public function dumpSql($format = false)
        {
            return \Illuminate\Database\Query\Builder::dumpSql($format = false);
        }

        /**
         * @param bool $format
         *
         * @return void
         */
        public function ddSql($format = false)
        {
            return \Illuminate\Database\Query\Builder::ddSql($format = false);
        }
    }
}

namespace Illuminate\Database\Eloquent\Relations {
    class Relation
    {
        /**
         * @param bool $format
         *
         * @return string
         */
        public function toRawSql($format = false)
        {
            return \Illuminate\Database\Eloquent\Builder::toRawSql($format = false);
        }

        /**
         * @param bool $format
         *
         * @return void
         */
        public function dumpSql($format = false)
        {
            return \Illuminate\Database\Eloquent\Builder::dumpSql($format = false);
        }

        /**
         * @param bool $format
         *
         * @return void
         */
        public function ddSql($format = false)
        {
            return \Illuminate\Database\Eloquent\Builder::ddSql($format = false);
        }
    }
}
