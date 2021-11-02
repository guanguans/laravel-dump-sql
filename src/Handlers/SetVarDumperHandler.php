<?php

/*
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql\Handlers;

use Guanguans\LaravelDumpSql\ContextProvider\RequestContextProvider;
use Guanguans\LaravelDumpSql\Dumper;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\ContextProvider\SourceContextProvider;
use Symfony\Component\VarDumper\Server\Connection;
use Symfony\Component\VarDumper\Server\DumpServer;
use Symfony\Component\VarDumper\VarDumper;

class SetVarDumperHandler
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function __invoke()
    {
        $this->app->when(DumpServer::class)->needs('$host')->give($host = $this->app['config']->get('dump-sql.host'));

        $connection = new Connection($host, [
            'request' => new RequestContextProvider($this->app['request']),
            // 'source' => new SourceContextProvider('utf-8', base_path()),
        ]);

        if (! $this->isCanWrited($connection)) {
            return;
        }

        VarDumper::setHandler(function ($var) use ($connection) {
            $this->app->make(Dumper::class, ['connection' => $connection])->dump($var);
        });

        $this->app->call(ListenedSqlHandler::class, [
            'target' => 'dump',
        ]);
    }

    protected function isCanWrited(Connection $connection)
    {
        $data = (new VarCloner())->cloneVar('===================================================================================================');

        return $connection->write($data);
    }
}
