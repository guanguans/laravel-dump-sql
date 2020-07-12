# laravel-dump-sql

> Easy output of SQL statements for laravel framework.

<p align="center"><img src="./docs/usage.png"></p>

![CI](https://github.com/guanguans/laravel-dump-sql/workflows/CI/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/guanguans/laravel-dump-sql/v)](//packagist.org/packages/guanguans/laravel-dump-sql)
[![Total Downloads](https://poser.pugx.org/guanguans/laravel-dump-sql/downloads)](//packagist.org/packages/guanguans/laravel-dump-sql)
[![License](https://poser.pugx.org/guanguans/laravel-dump-sql/license)](//packagist.org/packages/guanguans/laravel-dump-sql)

## Installing

``` shell
$ composer require guanguans/laravel-dump-sql -v
```

### Publish

```php
$ php artisan vendor:publish --provider="Guanguans\\LaravelDumpSql\\ServiceProvider"
$ php artisan vendor:publish --tag=laravel-dump-sql
```

## Usage (Custom method name. `config/dumpsql.php`)

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

## Testing

``` shell
$ composer test
```

## License

[MIT](LICENSE)
