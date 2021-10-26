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
        $registerDatabaseBuilderMethod = function ($methodName, Closure $closure) {
            return $this->registerDatabaseBuilderMethod($methodName, $closure);
        };
        $isRegister = $registerDatabaseBuilderMethod->call($this->provider, 'toRawSql', function () {
            return true;
        });
        $this->assertNull($isRegister);

        $registerDatabaseBuilderMethod = function ($methodName, Closure $closure) {
            return $this->registerDatabaseBuilderMethod($methodName, $closure);
        };
        $isRegister = $registerDatabaseBuilderMethod->call($this->provider, 'dumpSql', function () {
            return true;
        });
        $this->assertNull($isRegister);

        $registerDatabaseBuilderMethod = function ($methodName, Closure $closure) {
            return $this->registerDatabaseBuilderMethod($methodName, $closure);
        };
        $isRegister = $registerDatabaseBuilderMethod->call($this->provider, 'ddSql', function () {
            return true;
        });
        $this->assertNull($isRegister);

        // $this->provider->boot();
        // $this->markTestIncomplete();
        // $this->markTestSkipped();
        // $this->assertTrue(method_exists($this->app->make(QueryBuilder::class), 'toRawSql'));
        // $this->assertTrue(method_exists($this->app->make(QueryBuilder::class), 'dumpSql'));
        // $this->assertTrue(method_exists($this->app->make(QueryBuilder::class), 'ddSql'));
    }

    public function testRegisterDatabaseBuilderMethod()
    {
        $registerDatabaseBuilderMethod = function ($methodName, Closure $closure) {
            return $this->registerDatabaseBuilderMethod($methodName, $closure);
        };

        $isRegister = $registerDatabaseBuilderMethod->call($this->provider, 'mack_str', function () {
            return true;
        });

        $this->assertNull($isRegister);
    }

    public function testRegisterDatabaseBuilderMethodInvalidArgumentException()
    {
        $methodName = 'dd';

        $this->expectException(InvalidArgumentException::class);

        $registerDatabaseBuilderMethod = function ($methodName, Closure $closure) {
            return $this->registerDatabaseBuilderMethod($methodName, $closure);
        };

        $registerDatabaseBuilderMethod->call($this->provider, $methodName, function () {
            return true;
        });
    }

    public function testRegisterDatabaseBuilderMethodInvalidArgumentMethodException()
    {
        $this->expectError();

        $registerDatabaseBuilderMethod = function ($methodName, Closure $closure) {
            return $this->registerDatabaseBuilderMethod($methodName, $closure);
        };

        $registerDatabaseBuilderMethod->call($this->provider, ['mack_str'], function () {
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
