<p align="center"><img src="./docs/usage.png"></p>

# laravel-dump-sql

> Easy output of complete SQL statements for laravel framework.

> The sql statement obtained by the query construction method tolar in laravel is not bound to the conditional parameters, similar to `select * fromuserswhereid= ?`, so I wrote an extension package laravel-dump-sql to get the complete sql statement.

![CI](https://github.com/guanguans/laravel-dump-sql/workflows/CI/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/guanguans/laravel-dump-sql/v)](//packagist.org/packages/guanguans/laravel-dump-sql)
[![Total Downloads](https://poser.pugx.org/guanguans/laravel-dump-sql/downloads)](//packagist.org/packages/guanguans/laravel-dump-sql)
[![License](https://poser.pugx.org/guanguans/laravel-dump-sql/license)](//packagist.org/packages/guanguans/laravel-dump-sql)

## Requirements

* laravel >= 5.1

## Installing

``` shell
$ composer require guanguans/laravel-dump-sql -v
```

### Publish provider

```php
$ php artisan vendor:publish --provider="Guanguans\\LaravelDumpSql\\ServiceProvider"
```

## Usage

``` php
// Get sql statement.
User::where('id', 1)->toRawSql();
DB::table('user')->where('id', 1)->toRawSql();

// Print SQL statements.
User::where('id', 1)->dumpSql();
DB::table('user')->where('id', 1)->dumpSql();

// Print SQL statements and exit.
User::where('id', 1)->ddSql();
DB::table('user')->where('id', 1)->ddSql();
```

### Custom method name

#### Publish config

```php
$ php artisan vendor:publish --tag="laravel-dump-sql"
```

#### Custom method name in the file `config/dumpsql.php`

``` php
<?php

return [
    /*
     * Get sql statement.
     */
    'to_raw_sql' => 'Your favorite method name',

    /*
     * Print SQL statements.
     */
    'dump_sql' => 'Your favorite method name',

    /*
     * Print SQL statements and exit.
     */
    'dd_sql' => 'Your favorite method name',
];
```

## Testing

``` shell
$ composer test
```

## License

[MIT](LICENSE)
