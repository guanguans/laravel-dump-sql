<?php

/**
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql\Handlers;

use Guanguans\LaravelDumpSql\ContextProviders\RequestContextProvider;
use Guanguans\LaravelDumpSql\Dumper;
use Illuminate\Container\Container;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Server\Connection;
use Symfony\Component\VarDumper\Server\DumpServer;
use Symfony\Component\VarDumper\VarDumper;

class SetVarDumperHandler
{
    public const CONNECTION_FLAG = 'laravel-dump-sql';

    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function __invoke()
    {
        $this->app->when(DumpServer::class)->needs('$host')->give($host = $this->app['config']->get('dump-sql.host'));

        $connection = new Connection($host, [
            'request' => new RequestContextProvider($this->app['request']),
        ]);

        if (! $this->canWrited($connection)) {
            return;
        }

        VarDumper::setHandler(function ($var) use ($connection) {
            $this->app->make(Dumper::class, ['connection' => $connection])->dump($var);
        });

        enable_dump_listened_sql();
    }

    protected function canWrited(Connection $connection)
    {
        $data = (new VarCloner())->cloneVar(self::CONNECTION_FLAG);

        return $connection->write($data);
    }
}
