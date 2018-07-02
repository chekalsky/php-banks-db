<?php
declare(strict_types=1);

namespace BankDb;

use PHPUnit\Framework\TestCase;

class BankDbTest extends TestCase
{
    /**
     * @var BankDb
     */
    protected $bank_db;

    protected function setUp()
    {
        $this->bank_db = new BankDb();
    }

    public function testCanBeConstructed(): void
    {
        $this->assertNotEmpty($this->bank_db);
    }

    public function testExceptionIfDatabaseNotFound(): void
    {
        $this->expectException(BankDbException::class);

        new BankDb('/unknown/path');
    }

    public function testGetBankInfoIsWorking(): void
    {
        $bank_info = $this->bank_db->getBankInfo('400000');

        $this->assertTrue($bank_info->isUnknown());
    }
}
