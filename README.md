# PHP Banks DB

[![PHP Tests](https://github.com/chekalsky/php-banks-db/actions/workflows/ci.yml/badge.svg)](https://github.com/chekalsky/php-banks-db/actions/workflows/ci.yml)

> It is a PHP port of [ramoona's banks-db](https://github.com/ramoona/banks-db).

Returns bank's name and brand color by bank card number's first digits (BIN, Issuer Identification Numbers, IIN).

### Installation

```
composer require chekalskiy/php-banks-db
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

#### Database update
Database updates from [original library](https://github.com/ramoona/banks-db) by me. To update php cache file after manual database change run `composer rebuild`.

### Contributions

Feel free to open [an issue](https://github.com/chekalskiy/php-banks-db/issues) on every question you have. If you have new prefixes for database please commit them to [ramoona/banks-db repository](https://github.com/ramoona/banks-db) — I will update them once your PR will me merged there.

---

> It's a community driven database, so it can potentially contains mistakes.

For UI examples see the [original library](https://github.com/ramoona/banks-db).
