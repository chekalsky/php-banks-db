<?php
declare(strict_types=1);

namespace BankDb;

class BankDb
{
    /**
     * @var array
     */
    protected $database = [];

    /**
     * @var string
     */
    protected $database_file_path;

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
        $this->loadDatabase();
    }

    /**
     * @param string $card_number
     *
     * @return BankInfo
     */
    public function getBankInfo(string $card_number): BankInfo
    {
        $card_number = preg_replace('/\D/', '', $card_number);

        for ($l = $this->getMaxPrefixLength(); $l >= $this->getMinPrefixLength(); $l--) {
            $prefix = substr((string) $card_number, 0, $l);

            $bank_id = $this->getBankIdByPrefix($prefix);

            if ($bank_id > 0) {
                return new BankInfo($this->getBankInfoFromDatabase($bank_id), $prefix);
            }
        }

        return new BankInfo([], $card_number);
    }

    /**
     * Database init
     *
     * @param string|null $db_file_path
     *
     * @throws BankDbException
     */
    protected function initializeDatabase(string $db_file_path = null)
    {
        if ($db_file_path === null) {
            $db_file_path = __DIR__ . '/../db/bank_db.php';
        }

        if (!is_readable($db_file_path)) {
            throw new BankDbException('Cannot find DB file');
        }

        $this->database_file_path = $db_file_path;
    }

    /**
     * Database preload
     */
    protected function loadDatabase()
    {
        $this->database = include $this->database_file_path;
    }

    /**
     * What is the maximum length of prefix in database
     *
     * @return int
     */
    protected function getMaxPrefixLength(): int
    {
        return $this->database['max_length'] ?? 6;
    }

    /**
     * What is the minimum length of prefix in database
     *
     * @return int
     */
    protected function getMinPrefixLength(): int
    {
        return $this->database['min_length'] ?? 5;
    }

    /**
     * @param string $prefix
     *
     * @return int `0` if not found
     */
    protected function getBankIdByPrefix(string $prefix): int
    {
        if (isset($this->database['prefixes'][(int) $prefix])) {
            return (int) $this->database['prefixes'][(int) $prefix];
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
