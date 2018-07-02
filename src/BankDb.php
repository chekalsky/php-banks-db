<?php
declare(strict_types=1);

namespace BankDb;

class BankDb
{
    protected $database = [];

    /**
     * BankDb constructor.
     *
     * @param string $db_file_path
     *
     * @throws BankDbException
     */
    public function __construct(string $db_file_path = './../db/bank_db.php')
    {
        if (!is_readable($db_file_path)) {
            throw new BankDbException('Cannot find DB file');
        }

        $this->database = include $db_file_path;
    }

    /**
     * @param string $card_number
     *
     * @throws BankDbNotFoundException if bank was not found
     * @throws BankDbException         if internal error happened
     *
     * @return BankInfo
     */
    public function getBankInfo(string $card_number): BankInfo
    {
        $card_number = preg_replace('/\D/', '', $card_number);

        for ($l = $this->database['max_length']; $l >= $this->database['min_length']; $l--) {
            $prefix = substr((string) $card_number, 0, $l);

            if (isset($this->database['prefixes'][(int) $prefix])) {
                $bank_id = $this->database['prefixes'][(int) $prefix];

                return new BankInfo($this->database['banks'][$bank_id]);
            }
        }

        throw new BankDbNotFoundException('Bank not found');
    }
}
