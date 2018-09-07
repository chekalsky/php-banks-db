<?php
declare(strict_types=1);

namespace BankDb;

class BankDb
{
    /**
     * How many digits use to search
     */
    protected const PREFIX_LENGTH = 6;

    /**
     * @var array
     */
    protected $database = [];

    /**
     * BankDb constructor.
     *
     * @param string|null $db_file_path
     *
     * @throws BankDbException
     */
    public function __construct(string $db_file_path = null)
    {
        $this->initializeDatabase($db_file_path);
    }

    /**
     * @param string $card_number
     *
     * @return BankInfo
     */
    public function getBankInfo(string $card_number): BankInfo
    {
        $card_number = preg_replace('/\D/', '', $card_number);

        $prefix = str_pad(substr((string) $card_number, 0, static::PREFIX_LENGTH), static::PREFIX_LENGTH, '0');

        $bank_id = $this->getBankIdByPrefix((int) $prefix);

        if ($bank_id > 0) {
            return new BankInfo($this->getBankInfoFromDatabase($bank_id), $prefix);
        }

        return new BankInfo([], $card_number);
    }

    /**
     * Database init
     *
     * @param string|null $file_path
     *
     * @throws BankDbException
     */
    protected function initializeDatabase(string $file_path = null): void
    {
        if ($file_path === null) {
            $file_path = __DIR__ . '/../db/bank_db.php';
        }

        if (!is_readable($file_path)) {
            throw new BankDbException('Cannot find DB file');
        }

        $this->database = include $file_path;
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
