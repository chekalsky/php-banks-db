# PHP Banks DB

[![Build Status](https://travis-ci.com/chekalskiy/php-bank-db.svg?branch=master)](https://travis-ci.com/chekalskiy/php-bank-db) [![codecov](https://codecov.io/gh/chekalskiy/php-bank-db/branch/master/graph/badge.svg)](https://codecov.io/gh/chekalskiy/php-bank-db)

> It is a PHP port of [ramoona's banks-db](https://github.com/ramoona/banks-db).

Returns bank's name and brand color by bank card number's first digits (BIN, Issuer Identification Numbers, IIN).

### Installation

```
composer require chekalskiy/php-bank-db
```

### Basic usage

```php
$card_prefix = '5275 9400 0000 0000'; // we only need first 6 digits but it could be the whole card number

try {
    $bank_db = new BankDb();
    $bank_info = $bank_db->getBankInfo($card_prefix);

    $result = [
        'is_unknown' => $bank_info->isUnknown(), // is bank unknown
        'name' => $bank_info->getTitle(true),
        'color' => $bank_info->getColor(),
        'type' => $bank_info->getCardType(),
    ];

    return $result;
} catch (BankDbException $e) {
    // todo handle exception
}
```

### About database

We use simple PHP-file with an array inside (it's regenerates every time ramoona's repository is updated). It's very fast and simple way to work with data because of opcache enabled by default in PHP 7. But you can extend `BankDB` class to make it work with redis or something, but for most cases compiled php-file is OK.

### Contributions

Feel free to open [an issue](https://github.com/chekalskiy/php-bank-db/issues) on every question you have.

---

> It's a community driven database, so it can potentially contains mistakes.

For UI examples see the [original library](https://github.com/ramoona/banks-db).
