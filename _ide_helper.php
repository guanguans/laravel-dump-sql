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
            return \Illuminate\Database\Query\Builder::toRawSql($format);
        }

        /**
         * @param bool $format
         *
         * @return void
         */
        public function dumpSql($format = false)
        {
            return \Illuminate\Database\Query\Builder::dumpSql($format);
        }

        /**
         * @param bool $format
         *
         * @return void
         */
        public function ddSql($format = false)
        {
            return \Illuminate\Database\Query\Builder::ddSql($format);
        }

        /**
         * @return void
         */
        public function listenedSql(string $target): \Illuminate\Database\Query\Builder
        {
            return \Illuminate\Database\Query\Builder::listenedSql($target);
        }

        /**
         * @return void
         */
        public function logListenedSql(): \Illuminate\Database\Query\Builder
        {
            return \Illuminate\Database\Query\Builder::logListenedSql();
        }

        /**
         * @return void
         */
        public function dumpListenedSql(): \Illuminate\Database\Query\Builder
        {
            return \Illuminate\Database\Query\Builder::dumpListenedSql();
        }

        /**
         * @return void
         */
        public function ddListenedSql(): \Illuminate\Database\Query\Builder
        {
            return \Illuminate\Database\Query\Builder::ddListenedSql();
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
            return \Illuminate\Database\Query\Builder::toRawSql($format);
        }

        /**
         * @param bool $format
         *
         * @return void
         */
        public function dumpSql($format = false)
        {
            return \Illuminate\Database\Query\Builder::dumpSql($format);
        }

        /**
         * @param bool $format
         *
         * @return void
         */
        public function ddSql($format = false)
        {
            return \Illuminate\Database\Query\Builder::ddSql($format);
        }

        /**
         * @return void
         */
        public function listenedSql(string $target): \Illuminate\Database\Query\Builder
        {
            return \Illuminate\Database\Query\Builder::listenedSql($target);
        }

        /**
         * @return void
         */
        public function logListenedSql(): \Illuminate\Database\Query\Builder
        {
            return \Illuminate\Database\Query\Builder::logListenedSql();
        }

        /**
         * @return void
         */
        public function dumpListenedSql(): \Illuminate\Database\Query\Builder
        {
            return \Illuminate\Database\Query\Builder::dumpListenedSql();
        }

        /**
         * @return void
         */
        public function ddListenedSql(): \Illuminate\Database\Query\Builder
        {
            return \Illuminate\Database\Query\Builder::ddListenedSql();
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
            return \Illuminate\Database\Eloquent\Builder::toRawSql($format);
        }

        /**
         * @param bool $format
         *
         * @return void
         */
        public function dumpSql($format = false)
        {
            return \Illuminate\Database\Eloquent\Builder::dumpSql($format);
        }

        /**
         * @param bool $format
         *
         * @return void
         */
        public function ddSql($format = false)
        {
            return \Illuminate\Database\Eloquent\Builder::ddSql($format);
        }

        /**
         * @return void
         */
        public function listenedSql(string $target): \Illuminate\Database\Eloquent\Builder
        {
            return \Illuminate\Database\Eloquent\Builder::listenedSql($target);
        }

        /**
         * @return void
         */
        public function logListenedSql(): \Illuminate\Database\Eloquent\Builder
        {
            return \Illuminate\Database\Eloquent\Builder::logListenedSql();
        }

        /**
         * @return void
         */
        public function dumpListenedSql(): \Illuminate\Database\Eloquent\Builder
        {
            return \Illuminate\Database\Eloquent\Builder::dumpListenedSql();
        }

        /**
         * @return void
         */
        public function ddListenedSql(): \Illuminate\Database\Eloquent\Builder
        {
            return \Illuminate\Database\Eloquent\Builder::ddListenedSql();
        }
    }
}
