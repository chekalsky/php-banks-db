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
     * @var int
     */
    protected $prefix_length = 6;

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

        $prefix = str_pad(substr((string) $card_number, 0, $this->prefix_length), $this->prefix_length, '0');

        $bank_id = $this->getBankIdByPrefix($prefix);

        if ($bank_id > 0) {
            return new BankInfo($this->getBankInfoFromDatabase($bank_id), $prefix);
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
    protected function initializeDatabase(string $db_file_path = null): void
    {
        if ($db_file_path === null) {
            $db_file_path = __DIR__ . '/../db/bank_db.php';
        }

        if (!is_readable($db_file_path)) {
            throw new BankDbException('Cannot find DB file');
        }

        $this->database = include $db_file_path;
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
