# PHP Banks DB

> It is a PHP port of [ramoona's banks-db](https://github.com/ramoona/banks-db).

Returns bank's name and brand color by bank card number's first digits (BIN, Issuer Identification Numbers, IIN).

### Installation

```
composer require chekalskiy/php-bank-db
```

### Basic usage

```php
$card_prefix = $_GET['card_prefix']; // we only need first 6 digits

try {
    $bank_db = new BankDb();
    $bank_info = $bank_db->getBankInfo($card_prefix);

    $result = [
        'is_unknown' => $bank_info->isUnknown(), // is bank unknown
        'name' => $bank_info->getName(true),
        'color' => $bank_info->getColor(),
        'type' => $bank_info->getCardType(),
    ];

    return $result;
} catch (BankDbException $e) {
    // todo handle exception
}
```

### Contributions

Feel free to open [an issue](https://github.com/chekalskiy/php-bank-db/issues) on every question you have.

---

> It's a community driven database, so it can potentially contains mistakes.

For UI examples see the [original library](https://github.com/ramoona/banks-db).
