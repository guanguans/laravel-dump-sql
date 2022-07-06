<?php

/**
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql\Commands;

use Guanguans\LaravelDumpSql\CliDescriptor;
use Illuminate\Console\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Server\DumpServer;

/**
 * This file is modified from `beyondcode/laravel-dump-server`.
 */
class DumpSqlServerCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'server:dump-sql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the dump sql server to collect sql information.';

    /**
     * The Dump server.
     *
     * @var \Symfony\Component\VarDumper\Server\DumpServer
     */
    protected $server;

    /**
     * DumpServerCommand constructor.
     *
     * @return void
     */
    public function __construct(DumpServer $server)
    {
        $this->server = $server;

        parent::__construct();
    }

    /**
     * Handle the command.
     *
     * @return void
     */
    public function handle()
    {
        $descriptor = new CliDescriptor(new CliDumper());

        $io = new SymfonyStyle($this->input, $this->output);

        $errorIo = $io->getErrorStyle();
        $errorIo->title('Laravel Dump Sql Server');

        $this->server->start();

        $errorIo->success(sprintf('Server listening on %s', $this->server->getHost()));
        $errorIo->comment('Quit the server with CONTROL-C.');

        $this->server->listen(function (Data $data, array $context, int $clientId) use ($descriptor, $io) {
            $descriptor->describe($io, $data, $context, $clientId);
        });
    }
}
