<?php
declare(strict_types=1);

namespace BankDb;

use PHPUnit\Framework\TestCase;

class BankInfoTest extends TestCase
{
    /**
     * @var BankDb
     */
    protected $bank_db;

    protected function setUp()
    {
        $this->bank_db = new BankDb();
    }

    public function testAnyInput(): void
    {
        $bank_info_1 = $this->bank_db->getBankInfo('5321 30');
        $bank_info_2 = $this->bank_db->getBankInfo('532130');
        $bank_info_3 = $this->bank_db->getBankInfo('5!3_2l1$3*0');
        $bank_info_4 = $this->bank_db->getBankInfo('5321300000000000');

        $this->assertFalse($bank_info_1->isUnknown());
        $this->assertSame($bank_info_2->getTitle(), $bank_info_1->getTitle());
        $this->assertSame($bank_info_3->getTitle(), $bank_info_1->getTitle());
        $this->assertSame($bank_info_4->getTitle(), $bank_info_1->getTitle());
    }

    public function testCardTypes(): void
    {
        // amex
        $this->assertSame('amex', $this->bank_db->getBankInfo('340000')->getCardType());
        $this->assertSame('amex', $this->bank_db->getBankInfo('370000')->getCardType());

        // unionpay
        $this->assertSame('unionpay', $this->bank_db->getBankInfo('620000')->getCardType());
        $this->assertSame('unionpay', $this->bank_db->getBankInfo('880000')->getCardType());

        // discover
        $this->assertSame('discover', $this->bank_db->getBankInfo('601100')->getCardType());
        $this->assertSame('discover', $this->bank_db->getBankInfo('640000')->getCardType());
        $this->assertSame('discover', $this->bank_db->getBankInfo('650000')->getCardType());

        // electron
        $this->assertSame('electron', $this->bank_db->getBankInfo('402600')->getCardType());
        $this->assertSame('electron', $this->bank_db->getBankInfo('417500')->getCardType());

        // interpayment
        $this->assertSame('interpayment', $this->bank_db->getBankInfo('636000')->getCardType());

        // maestro
        $this->assertSame('maestro', $this->bank_db->getBankInfo('501800')->getCardType());
        $this->assertSame('maestro', $this->bank_db->getBankInfo('561200')->getCardType());
        $this->assertSame('maestro', $this->bank_db->getBankInfo('589300')->getCardType());
        $this->assertSame('maestro', $this->bank_db->getBankInfo('630400')->getCardType());
        $this->assertSame('maestro', $this->bank_db->getBankInfo('639000')->getCardType());

        // visa
        $this->assertSame('visa', $this->bank_db->getBankInfo('411111')->getCardType());
        $this->assertSame('visa', $this->bank_db->getBankInfo('400000')->getCardType());

        // mastercard
        $this->assertSame('mastercard', $this->bank_db->getBankInfo('510000')->getCardType());
        $this->assertSame('mastercard', $this->bank_db->getBankInfo('520000')->getCardType());
        $this->assertSame('mastercard', $this->bank_db->getBankInfo('530000')->getCardType());
        $this->assertSame('mastercard', $this->bank_db->getBankInfo('540000')->getCardType());
        $this->assertSame('mastercard', $this->bank_db->getBankInfo('550000')->getCardType());
        $this->assertSame('mastercard', $this->bank_db->getBankInfo('222100')->getCardType());
        $this->assertSame('mastercard', $this->bank_db->getBankInfo('259000')->getCardType());
        $this->assertSame('mastercard', $this->bank_db->getBankInfo('272000')->getCardType());

        // diners
        $this->assertSame('diners', $this->bank_db->getBankInfo('360000')->getCardType());
        $this->assertSame('diners', $this->bank_db->getBankInfo('300000')->getCardType());
        $this->assertSame('diners', $this->bank_db->getBankInfo('305000')->getCardType());
        $this->assertSame('diners', $this->bank_db->getBankInfo('309500')->getCardType());
        $this->assertSame('diners', $this->bank_db->getBankInfo('380000')->getCardType());
        $this->assertSame('diners', $this->bank_db->getBankInfo('390000')->getCardType());

        // jcb
        $this->assertSame('jcb', $this->bank_db->getBankInfo('352800')->getCardType());
        $this->assertSame('jcb', $this->bank_db->getBankInfo('355000')->getCardType());
        $this->assertSame('jcb', $this->bank_db->getBankInfo('358900')->getCardType());

        // mir
        $this->assertSame('mir', $this->bank_db->getBankInfo('220000')->getCardType());
        $this->assertSame('mir', $this->bank_db->getBankInfo('220400')->getCardType());
    }

    public function testBanks(): void
    {
        $this->assertSame('Rocketbank', $this->bank_db->getBankInfo('532130')->getTitle(false));
        $this->assertSame('Alfa-Bank', $this->bank_db->getBankInfo('428906')->getTitle(false));

        // $this->assertSame('Millennium', $this->bank_db->getBankInfo('487474')->getTitle(false));
    }
}
