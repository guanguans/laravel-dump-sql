# laravel-dump-sql

[![ci](https://github.com/guanguans/laravel-dump-sql/actions/workflows/ci.yml/badge.svg)](https://github.com/guanguans/laravel-dump-sql/actions/workflows/ci.yml)
[![Latest Stable Version](https://poser.pugx.org/guanguans/laravel-dump-sql/v)](//packagist.org/packages/guanguans/laravel-dump-sql)
[![Total Downloads](https://poser.pugx.org/guanguans/laravel-dump-sql/downloads)](//packagist.org/packages/guanguans/laravel-dump-sql)
[![License](https://poser.pugx.org/guanguans/laravel-dump-sql/license)](//packagist.org/packages/guanguans/laravel-dump-sql)

> Easy output of complete SQL statements in laravel framework. - 在 laravel 中轻松容易的输出完整的 sql 语句。

> The sql statement obtained by the query construction method in laravel is not bound to the conditional parameters, similar to `select * from users where id= ?`. This expansion pack can help you get a complete sql statement. - laravel 中查询构造方法得到的 sql 语句没有绑定条件参数，类似于`select * from users where id= ?`。这个扩展包可辅助你获取完整的 sql 语句。

```php
User::query()->where('id', 1)->dd();
```

![](docs/dd.png)

```php
User::query()->where('id', 1)->ddSql();
```

![](docs/ddSql.png)

## Requirements

* laravel >= 5.1

## Installing

```shell
$ composer require guanguans/laravel-dump-sql -v
```

## Configuration

### laravel

#### Publish provider(laravel < 5.5)

```shell
$ php artisan vendor:publish --provider="Guanguans\\LaravelDumpSql\\ServiceProvider"
```

### lumen

#### Bootstrap file changes

Add the following snippet to the bootstrap/app.php file under the `Register Service Providers` section as follows:

```php
$app->register(\Guanguans\LaravelDumpSql\ServiceProvider::class);
```

## Usage

```php
// Get sql statement.
User::query()->where('id', 1)->toRawSql();
DB::table('user')->where('id', 1)->toRawSql();
// Get formatted sql statement.
User::query()->where('id', 1)->toRawSql(true);
DB::table('user')->where('id', 1)->toRawSql(true);

// Print sql statements.
User::query()->where('id', 1)->dumpSql();
DB::table('user')->where('id', 1)->dumpSql();
// Print formatted sql statements.
User::query()->where('id', 1)->dumpSql(true);
DB::table('user')->where('id', 1)->dumpSql(true);

// Print sql statements and exit.
User::query()->where('id', 1)->ddSql();
DB::table('user')->where('id', 1)->ddSql();
// Print formatted sql statements and exit.
User::query()->where('id', 1)->ddSql(true);
DB::table('user')->where('id', 1)->ddSql(true);
```

### Custom method name

#### Publish config

```php
$ php artisan vendor:publish --tag="laravel-dump-sql"
```

#### Custom method name in the file `config/dumpsql.php`

```php
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

```shell
$ composer test
```

## License

[MIT](LICENSE)
