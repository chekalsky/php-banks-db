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
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var array
     */
    protected static $card_prefixes = [
        'electron' => '/^(4026|417500|4405|4508|4844|4913|4917)/',
        'interpayment' => '/^636/',
        'unionpay' => '/^62/',
        'maestro' => '/^(50|56|57|58|6)/',
        'visa' => '/^4/',
        'mastercard' => '/^(5[1-5]|[2221-2720])/',
        'amex' => '/^3[47]/',
        'diners' => '/^3(?:0[0-5]|[68][0-9])/',
        'discover' => '/^6(?:011|5[0-9]{2})/',
        'jcb' => '/^(?:2131|1800|[3528-3589])/',
        'mir' => '/^220[0-4]/',
    ];

    /**
     * BankInfo constructor.
     *
     * @param array  $data
     * @param string $prefix
     *
     * @throws BankDbException
     */
    public function __construct(array $data, string $prefix = '')
    {
        if (!isset($data['name'])) {
            throw new BankDbException('Invalid bank data');
        }

        $this->data = $data;
        $this->prefix = $prefix;
    }

    public function getName(): string
    {
        return $this->data['name'];
    }

    public function getTitle(bool $is_local = true): string
    {
        if ($is_local && isset($this->data['localTitle'])) {
            return $this->data['localTitle'];
        }

        return $this->data['engTitle'] ?? $this->getName();
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
     * Card type from prefix
     *
     * For possible types see `$card_prefixes`
     *
     * @return string
     */
    public function getCardType(): string
    {
        $prefix = $this->prefix;

        foreach (self::$card_prefixes as $card_type => $card_prefix) {
            if (preg_match($card_prefix, $prefix)) {
                return $card_type;
            }
        }

        return 'unknown';
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
}
