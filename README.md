# About Laravel Rajabiller

[![Tests](https://github.com/kevinpurwito/laravel-rajabiller/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/kevinpurwito/laravel-rajabiller/actions/workflows/run-tests.yml)
[![Code Style](https://github.com/kevinpurwito/laravel-rajabiller/actions/workflows/php-cs-fixer.yml/badge.svg?branch=main)](https://github.com/kevinpurwito/laravel-rajabiller/actions/workflows/php-cs-fixer.yml)
[![Psalm](https://github.com/kevinpurwito/laravel-rajabiller/actions/workflows/psalm.yml/badge.svg?branch=main)](https://github.com/kevinpurwito/laravel-rajabiller/actions/workflows/psalm.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/kevinpurwito/laravel-rajabiller.svg?style=flat-square)](https://packagist.org/packages/kevinpurwito/laravel-rajabiller)
[![Total Downloads](https://img.shields.io/packagist/dt/kevinpurwito/laravel-rajabiller.svg?style=flat-square)](https://packagist.org/packages/kevinpurwito/laravel-rajabiller)

Laravel Rajabiller is a package that integrates [Rajabiller](https://www.rajabiller.com/) for Laravel.

Refer to this [docs](https://www.rajabiller.com/docs/json).

## Installation

You can install the package via composer:

```bash
composer require kevinpurwito/laravel-rajabiller
```

## Configuration

The `vendor:publish` command will publish a file named `kp_rajabiller.php` within your laravel project config
folder `config/kp_rajabiller.php`.

Published Config File Contents

```php
[
    'env' => strtolower(env('KP_RB_ENV', 'dev')), // dev or prod

    'url' => strtolower(env('KP_RB_URL', 'https://rajabiller.fastpay.co.id/transaksi/json_devel.php')),

    'uid' => env('KP_RB_UID'),

    'pin' => env('KP_RB_PIN'),
];
```

Alternatively you can ignore the above publish command and add this following variables to your `.env` file.

```text
KP_RB_ENV=dev
KP_RB_UID=user
KP_RB_PIN=secret
```

## Auto Discovery

If you're using Laravel 5.5+ you don't need to manually add the service provider or facade. This will be
Auto-Discovered. For all versions of Laravel below 5.5, you must manually add the ServiceProvider & Facade to the
appropriate arrays within your Laravel project `config/app.php`

### Provider

```php
[
    Kevinpurwito\LaravelRajabiller\RajabillerServiceProvider::class,
];
```

### Alias / Facade

```php
[
    'Rajabiller' => Kevinpurwito\LaravelRajabiller\RajabillerFacade::class,
];
```

## Usage

```php
use Kevinpurwito\LaravelRajabiller\RajabillerFacade as Rajabiller;

// returns the balance that you have
Rajabiller::getBalance();

// returns the list of orders you created in certain date (accepts date in Y-m-d format)
Rajabiller::orders('2021-01-20');

// returns the list of items under certain group (accepts string groupCode)
Rajabiller::groupItems('TELKOMSEL');

// returns the details of item (accepts string itemCode)
Rajabiller::item('S5H'); // telkomsel pulsa 5rb

// purchase an item, either pulsa or game voucher
Rajabiller::purchase('TXxxx', 'S5H', '628xxxx', 'pulsa'); // telkomsel pulsa 5rb

// inquiry for ppob (PLN, TELKOM, etc) price before paying
Rajabiller::inquiry('TXxxx', 'PLN', '123xxxx');

// pay for ppob (PLN, TELKOM, etc)
Rajabiller::pay('TXxxx', 'PLN', '123xxxx');

```

> Be careful! You can only do 3 inquiries per day for 1 customerId for each item. 
> For example. you can only inquire about a PLN charge for 1 customerId 3 times, after that you have to pay it or inquire again tomorrow.

### Handling the response

```php
use Kevinpurwito\LaravelRajabiller\RajabillerFacade as Rajabiller;

$response = Rajabiller::item('S5H'); // telkomsel pulsa 5rb

if ($response->getStatusCode() == 200) {
    // if you want to check the response body, such as `HARGA` you can do this:
    $content = json_decode($response->getBody()->getContents());
    dump($content);
//    {
//      "KODE_PRODUK": "S5H",
//      "UID": "UID",
//      "PIN": "PIN",
//      "STATUS": "00",
//      "KET": "SUKSES",
//      "HARGA": "5435",
//      "ADMIN": "",
//      "KOMISI": "0",
//      "PRODUK": "TELKOMSEL SIMPATI / AS 5RB",
//      "STATUS_PRODUK": "AKTIF",
//    }
}

```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email [kevin.purwito@gmail.com](mailto:kevin.purwito@gmail.com)
instead of using the issue tracker.

## Credits

- [Kevin Purwito](https://github.com/kevinpurwito)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com)
by [Beyond Code](http://beyondco.de/)
with some modifications inspired from [PHP Package Skeleton](https://github.com/spatie/package-skeleton-php)
by [spatie](https://spatie.be/).
