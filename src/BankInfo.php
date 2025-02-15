<?php

declare(strict_types=1);

namespace BankDb;

/**
 * Class BankInfo
 *
 * Stores and returns information about bank
 *
 * @package BankDb
 */
class BankInfo
{
    /**
     * How many digits to store
     */
    protected const int PREFIX_LENGTH = 8;

    protected bool $is_unknown = false;

    protected string $card_type = 'unknown';

    /**
     * @see https://github.com/braintree/credit-card-type/blob/master/index.js
     * @see https://en.wikipedia.org/wiki/Payment_card_number
     * @see https://github.com/ramoona/banks-db/blob/2a882c921e4c4e1d1ee452e97671aedfbe325abe/type.js
     *
     * @var array
     */
    protected static array $card_prefixes = [
        'electron' => '/^(4026|417500|4405|4508|4844|4913|4917)/',
        'interpayment' => '/^636/',
        'unionpay' => '/^(62|88)/',
        'discover' => '/^6(?:011|4|5)/',
        'maestro' => '/^(50|5[6-9]|6)/',
        'visa' => '/^4/',
        'mastercard' => '/^(5[1-5]|(?:222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720))/', // [2221-2720]
        'amex' => '/^3[47]/',
        'diners' => '/^3(?:0([0-5]|95)|[689])/',
        'jcb' => '/^(?:2131|1800|(?:352[89]|35[3-8][0-9]))/', // 3528-3589
        'mir' => '/^220[0-4]/',
    ];

    public function __construct(protected array $data, string $prefix = '')
    {
        if (!isset($this->data['name'])) {
            $this->makeUnknown();
        }

        $prefix = substr($prefix, 0, static::PREFIX_LENGTH);

        foreach (static::$card_prefixes as $card_type => $card_prefix) {
            if (preg_match($card_prefix, $prefix)) {
                $this->card_type = $card_type;
                break;
            }
        }
    }

    public function getTitle(bool $is_local = true): string
    {
        if ($is_local && isset($this->data['localTitle'])) {
            return $this->data['localTitle'];
        }

        return $this->data['engTitle'] ?? $this->data['name'];
    }

    public function getCountryCode(): string
    {
        return $this->data['country'];
    }

    public function getUrl(): string
    {
        return $this->data['url'];
    }

    /**
     * @return string in hex format `#0088cf`
     */
    public function getColor(): string
    {
        return $this->data['color'];
    }

    /**
     * @return bool returns true for banks with revoked license
     */
    public function isDefunct(): bool
    {
        return isset($this->data['defunct']) && $this->data['defunct'];
    }

    /**
     * @return bool
     */
    public function isUnknown(): bool
    {
        return $this->is_unknown;
    }

    /**
     * Card type from prefix
     *
     * For possible types see `$card_prefixes`
     *
     * @return string
     */
    public function getCardType(): string
    {
        return $this->card_type;
    }

    /**
     * Make this bank object unknown
     */
    protected function makeUnknown(): void
    {
        $this->is_unknown = true;
        $this->data = [
            'name' => 'unknown',
            'localTitle' => 'Unknown Bank',
            'engTitle' => 'Unknown Bank',
            'country' => 'us',
            'url' => '',
            'color' => '#ffffff',
        ];
    }
}
