<?php

/**
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql;

use Guanguans\LaravelDumpSql\Handlers\SetVarDumperHandler;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Command\Descriptor\DumpDescriptorInterface;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class CliDescriptor implements DumpDescriptorInterface
{
    private $dumper;

    private $lastIdentifier;

    private $supportsHref;

    public function __construct(CliDumper $dumper)
    {
        $this->dumper = $dumper;
        $this->supportsHref = method_exists(OutputFormatterStyle::class, 'setHref');
    }

    public function describe(OutputInterface $output, Data $data, array $context, int $clientId): void
    {
        if ($this->shouldntDescribe($data)) {
            return;
        }

        $io = $output instanceof SymfonyStyle ? $output : new SymfonyStyle(new ArrayInput([]), $output);
        $this->dumper->setColors($output->isDecorated());

        $lastIdentifier = $this->lastIdentifier;
        $this->lastIdentifier = $clientId;

        $section = "Received from client #$clientId";
        if (isset($context['request'])) {
            $request = $context['request'];
            $this->lastIdentifier = $request['identifier'];
            $section = sprintf('[%s] [%s] [%s]', date('Y-m-d H:i:s', (int) $context['timestamp']), $request['method'], $request['uri']);
        }

        if ($this->lastIdentifier !== $lastIdentifier) {
            $io->success($section);
        }

        $this->dumper->dump($data);
        $io->newLine();
    }

    protected function shouldntDescribe(Data $data): bool
    {
        return SetVarDumperHandler::CONNECTION_FLAG === $data->getValue();
    }

    protected function shouldDescribe(Data $data): bool
    {
        return ! $this->shouldntDescribe($data);
    }
}
