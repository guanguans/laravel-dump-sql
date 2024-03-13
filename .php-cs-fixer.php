<?php

/**
 * This file is part of the guanguans/laravel-dump-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

$header = <<<EOF
    This file is part of the guanguans/laravel-dump-sql.

    (c) guanguans <ityaozm@gmail.com>

    This source file is subject to the MIT license that is bundled.
    EOF;

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__.'/config',
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->exclude([
        '.github/',
        'doc/',
        'docs/',
        'vendor/',
    ])
    ->append(glob(__DIR__.'/{*,.*}.php', GLOB_BRACE))
    ->append([
        __DIR__.'/composer-updater',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->notName('_ide_helper.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PHP70Migration' => true,
        // '@PHP70Migration:risky' => true,
        '@PHP71Migration' => true,
        // '@PHP71Migration:risky' => true,
        '@PHP73Migration' => true,
        // '@PHP74Migration' => true,
        // '@PHP74Migration:risky' => true,
        // '@PHP80Migration' => true,
        // '@PHP80Migration:risky' => true,
        // '@PHP81Migration' => true,
        // '@PHP82Migration' => true,

        // '@PHPUnit75Migration:risky' => true,
        // '@PHPUnit84Migration:risky' => true,
        // '@PHPUnit100Migration:risky' => true,
        '@Symfony' => true,
        'header_comment' => [
            'header' => $header,
            'comment_type' => 'PHPDoc',
        ],
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'not_operator_with_successor_space' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'single_quote' => true,
        'class_attributes_separation' => true,
        'standardize_not_equals' => true,
        // 'trailing_comma_in_multiline' => true,
        // 'php_unit_construct' => true,
        // 'php_unit_strict' => true,
        // 'declare_strict_types' => true,
    ])
    ->setRiskyAllowed(false)
    ->setFinder($finder);
