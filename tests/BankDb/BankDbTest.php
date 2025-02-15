<?php

declare(strict_types=1);

namespace BankDb;

use PHPUnit\Framework\TestCase;

final class BankDbTest extends TestCase
{
    protected BankDb $bankDb;

    protected function setUp(): void
    {
        $this->bankDb = new BankDb();
    }

    public function testCanBeConstructed(): void
    {
        $this->assertNotEmpty($this->bankDb);
    }

    public function testExceptionIfDatabaseNotFound(): void
    {
        $this->expectException(BankDbException::class);

        new BankDb('/unknown/path');
    }

    public function testGetBankInfoIsWorking(): void
    {
        $bank_info = $this->bankDb->getBankInfo('400000');

        $this->assertTrue($bank_info->isUnknown());
    }
}
