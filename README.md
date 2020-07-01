# laravel-raw-sql

> Easy output of SQL statements for laravel framework.

![CI](https://github.com/guanguans/laravel-raw-sql/workflows/CI/badge.svg)

## Installing

``` shell
$ composer require guanguans/laravel-toRawSql -v
```

### publish

```php
$ php artisan vendor:publish --provider="Guanguans\\LaravelRawSql\\ServiceProvider"
```

## Usage

### code

``` php
// model
echo User::where('id', 1)->toRawSql();
// DB
echo DB::table('user')->where('id', 1)->toRawSql();
```

### output

``` sql
select * from `users` where `id` = 1
```

## License

[MIT](LICENSE)
