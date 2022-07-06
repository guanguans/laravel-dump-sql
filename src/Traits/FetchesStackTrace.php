<?php

/**
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql\Traits;

use Illuminate\Support\Str;

/**
 * This file is modified from `laravel/telescope`.
 */
trait FetchesStackTrace
{
    /**
     * Find the first frame in the stack trace outside of Laravel.
     *
     * @param string|array $forgetLines
     *
     * @return array|null
     */
    protected function getCallerFromStackTrace($forgetLines = 0)
    {
        $trace = collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))->forget($forgetLines);

        return $trace->first(function ($frame) {
            if (! isset($frame['file'])) {
                return false;
            }

            return ! Str::contains($frame['file'],
                base_path('vendor'.DIRECTORY_SEPARATOR.$this->ignoredVendorPath())
            );
        });
    }

    /**
     * Choose the frame outside of either Laravel or all packages.
     *
     * @return string|null
     */
    protected function ignoredVendorPath()
    {
        if (! ($this->options['ignore_packages'] ?? true)) {
            return 'laravel';
        }
    }
}
