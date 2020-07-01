# laravel-toRawSql

> Easy output of SQL statements for laravel framework.

![CI](https://github.com/guanguans/laravel-toRawSql/workflows/CI/badge.svg?branch=master)

## Installing

``` shell
$ composer require guanguans/laravel-toRawSql -v
```

### publish

```php
$ php artisan vendor:publish --provider="Guanguans\\LaravelToRawSql\\ServiceProvider"
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
