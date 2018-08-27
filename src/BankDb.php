<?php
declare(strict_types=1);

namespace BankDb;

class BankDb
{
    protected const PREFIX_LENGTH = 6;

    /**
     * @var array
     */
    protected $database = [];

    /**
     * BankDb constructor.
     *
     * @param string|null $dbFilePath
     *
     * @throws BankDbException
     */
    public function __construct(string $dbFilePath = null)
    {
        $this->initializeDatabase($dbFilePath);
    }

    /**
     * @param string $cardNumber
     *
     * @return BankInfo
     */
    public function getBankInfo(string $cardNumber): BankInfo
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);

        $prefix = str_pad(substr((string) $cardNumber, 0, static::PREFIX_LENGTH), static::PREFIX_LENGTH, '0');

        $bankId = $this->getBankIdByPrefix((int) $prefix);

        if ($bankId > 0) {
            return new BankInfo($this->getBankInfoFromDatabase($bankId), $prefix);
        }

        return new BankInfo([], $cardNumber);
    }

    /**
     * Database init
     *
     * @param string|null $filePath
     *
     * @throws BankDbException
     */
    protected function initializeDatabase(string $filePath = null): void
    {
        if ($filePath === null) {
            $filePath = __DIR__ . '/../db/bank_db.php';
        }

        if (!is_readable($filePath)) {
            throw new BankDbException('Cannot find DB file');
        }

        $this->database = include $filePath;
    }

    /**
     * @param int $prefix
     *
     * @return int `0` if not found
     */
    protected function getBankIdByPrefix(int $prefix): int
    {
        if (isset($this->database['prefixes'][$prefix])) {
            return (int) $this->database['prefixes'][$prefix];
        }

        return 0;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    protected function getBankInfoFromDatabase(int $id): array
    {
        return $this->database['banks'][$id];
    }
}
