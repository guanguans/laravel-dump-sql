<?php

/**
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Server\Connection;

/**
 * This file is modified from `beyondcode/laravel-dump-server`.
 */
class Dumper
{
    /**
     * The connection.
     *
     * @var \Symfony\Component\VarDumper\Server\Connection|null
     */
    private $connection;

    /**
     * Dumper constructor.
     *
     * @return void
     */
    public function __construct(Connection $connection = null)
    {
        $this->connection = $connection;
    }

    /**
     * Dump a value with elegance.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function dump($value)
    {
        if (class_exists(CliDumper::class)) {
            $data = $this->createVarCloner()->cloneVar($value);

            if (null === $this->connection || false === $this->connection->write($data)) {
                $dumper = new CliDumper();
                $dumper->dump($data);
            }
        } else {
            var_dump($value);
        }
    }

    protected function createVarCloner(): VarCloner
    {
        return new VarCloner();
    }
}
