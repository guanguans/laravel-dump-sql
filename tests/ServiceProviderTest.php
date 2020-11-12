<?php

/*
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Tests;

use Closure;
use Guanguans\LaravelDumpSql\ServiceProvider;
use InvalidArgumentException;

class ServiceProviderTest extends TestCase
{
    protected $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = $this->app->resolveProvider(ServiceProvider::class);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('dumpsql', [
            'to_raw_sql' => 'toRawSql',
            'dump_sql' => 'dumpSql',
            'dd_sql' => 'ddSql',
        ]);
    }

    public function testBoot()
    {
        $registerBuilderMacro = function ($macro, Closure $closure) {
            return $this->registerBuilderMacro($macro, $closure);
        };
        $isRegister = $registerBuilderMacro->call($this->provider, config('dumpsql.to_raw_sql', 'toRawSql'), function () {
            return true;
        });
        $this->assertTrue($isRegister);

        $registerBuilderMacro = function ($macro, Closure $closure) {
            return $this->registerBuilderMacro($macro, $closure);
        };
        $isRegister = $registerBuilderMacro->call($this->provider, config('dumpsql.dump_sql', 'dumpSql'), function () {
            return true;
        });
        $this->assertTrue($isRegister);

        $registerBuilderMacro = function ($macro, Closure $closure) {
            return $this->registerBuilderMacro($macro, $closure);
        };
        $isRegister = $registerBuilderMacro->call($this->provider, config('dumpsql.dd_sql', 'ddSql'), function () {
            return true;
        });
        $this->assertTrue($isRegister);

        // $this->provider->boot();
        // $this->markTestIncomplete();
        // $this->markTestSkipped();
        // $this->assertTrue(method_exists($this->app->make(QueryBuilder::class), 'toRawSql'));
        // $this->assertTrue(method_exists($this->app->make(QueryBuilder::class), 'dumpSql'));
        // $this->assertTrue(method_exists($this->app->make(QueryBuilder::class), 'ddSql'));
    }

    public function testRegisterEloquentBuilderMacro()
    {
        $registerBuilderMacro = function ($macro, Closure $closure) {
            return $this->registerBuilderMacro($macro, $closure);
        };

        $isRegister = $registerBuilderMacro->call($this->provider, 'mack_str', function () {
            return true;
        });

        $this->assertTrue($isRegister);
    }

    public function testRegisterBuilderMacro()
    {
        $registerEloquentBuilderMacro = function ($macro) {
            return $this->registerEloquentBuilderMacro($macro);
        };

        $isRegister = $registerEloquentBuilderMacro->call($this->provider, 'mack_str');

        $this->assertTrue($isRegister);
    }

    public function testRegisterBuilderMacroInvalidArgumentException()
    {
        $macro = 'dd';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('`Illuminate\Database\Query\Builder` already exists method.:%s', $macro));

        $registerBuilderMacro = function ($macro, Closure $closure) {
            return $this->registerBuilderMacro($macro, $closure);
        };

        $registerBuilderMacro->call($this->provider, $macro, function () {
            return true;
        });
    }

    public function testRegisterBuilderMacroInvalidArgumentMethodException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Macro name must be a string');

        $registerBuilderMacro = function ($macro, Closure $closure) {
            return $this->registerBuilderMacro($macro, $closure);
        };

        $registerBuilderMacro->call($this->provider, ['mack_str'], function () {
            return true;
        });
    }

    public function testSetupConfig()
    {
        $setupConfig = function () {
        };
        $isSetupConfig = $setupConfig->call($this->provider);
        $this->assertNull($isSetupConfig);
    }

    public function register()
    {
        $this->assertNull($this->provider->register());
    }
}
