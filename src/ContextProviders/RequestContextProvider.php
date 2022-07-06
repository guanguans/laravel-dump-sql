<?php

/**
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelDumpSql\ContextProviders;

use Illuminate\Http\Request;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\ContextProvider\ContextProviderInterface;

/**
 * This file is modified from `beyondcode/laravel-dump-server`.
 */
class RequestContextProvider implements ContextProviderInterface
{
    /**
     * The current request.
     *
     * @var \Illuminate\Http\Request|null
     */
    private $currentRequest;

    /**
     * The variable cloner.
     *
     * @var \Symfony\Component\VarDumper\Cloner\VarCloner
     */
    private $cloner;

    /**
     * RequestContextProvider constructor.
     *
     * @return void
     */
    public function __construct(Request $currentRequest = null)
    {
        $this->currentRequest = $currentRequest;
        $this->cloner = new VarCloner();
        $this->cloner->setMaxItems(0);
    }

    /**
     * Get the context.
     */
    public function getContext(): ?array
    {
        if (null === $this->currentRequest) {
            return null;
        }

        return [
            'uri' => $this->currentRequest->getUri(),
            'method' => $this->currentRequest->getMethod(),
            'identifier' => spl_object_hash($this->currentRequest),
        ];
    }
}
