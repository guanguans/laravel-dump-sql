<?php

/*
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

use Faker\Generator as Faker;
use Guanguans\LaravelDumpSqlTests\TestClasses\Models\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
