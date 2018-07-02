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
     * BankInfo constructor.
     *
     * @param array $data
     *
     * @throws BankDbException
     */
    public function __construct(array $data)
    {
        if (!isset($data['name'])) {
            throw new BankDbException('Invalid bank data');
        }

        $this->data = $data;
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
