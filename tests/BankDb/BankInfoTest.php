<?php

declare(strict_types=1);

namespace BankDb;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BankInfoTest extends TestCase
{
    protected BankDb $bankDb;
    protected string $validPrefix = '428906';

    protected function setUp(): void
    {
        $this->bankDb = new BankDb();
    }

    #[DataProvider('anyInputProvider')]
    public function testAnyInput(string $input, bool $expectedUnknown, ?string $compareWith = null): void
    {
        $bankInfo = $this->bankDb->getBankInfo($input);

        $this->assertSame($expectedUnknown, $bankInfo->isUnknown());

        if ($compareWith !== null) {
            $this->assertSame(
                $this->bankDb->getBankInfo($compareWith)->getTitle(),
                $bankInfo->getTitle()
            );
        }
    }

    public static function anyInputProvider(): array
    {
        return [
            'spaced input' => ['5321 30', false, '532130'],
            'clean input' => ['522319', false, '522319'],
            'special chars' => ['5!3_2l1$3*0', false, '532130'],
            'long number' => ['5321300000000000', false, '532130'],
            'short valid number' => ['48153', false, '48153'],
            'empty input' => ['', true, null],
        ];
    }

    #[DataProvider('cardTypesProvider')]
    public function testCardTypes(string $prefix, string $expectedType): void
    {
        $this->assertSame($expectedType, $this->bankDb->getBankInfo($prefix)->getCardType());
    }

    public static function cardTypesProvider(): array
    {
        return [
            'amex 1' => ['340000', 'amex'],
            'amex 2' => ['370000', 'amex'],
            'unionpay 1' => ['620000', 'unionpay'],
            'unionpay 2' => ['880000', 'unionpay'],
            'discover 1' => ['601100', 'discover'],
            'discover 2' => ['640000', 'discover'],
            'discover 3' => ['650000', 'discover'],
            'electron 1' => ['402600', 'electron'],
            'electron 2' => ['417500', 'electron'],
            'interpayment' => ['636000', 'interpayment'],
            'maestro 1' => ['501800', 'maestro'],
            'maestro 2' => ['561200', 'maestro'],
            'maestro 3' => ['589300', 'maestro'],
            'maestro 4' => ['630400', 'maestro'],
            'maestro 5' => ['639000', 'maestro'],
            'visa 1' => ['4', 'visa'],
            'visa 2' => ['411111', 'visa'],
            'visa 3' => ['400000', 'visa'],
            'mastercard 1' => ['51', 'mastercard'],
            'mastercard 2' => ['510000', 'mastercard'],
            'mastercard 3' => ['520000', 'mastercard'],
            'mastercard 4' => ['530000', 'mastercard'],
            'mastercard 5' => ['540000', 'mastercard'],
            'mastercard 6' => ['550000', 'mastercard'],
            'mastercard 7' => ['222100', 'mastercard'],
            'mastercard 8' => ['259000', 'mastercard'],
            'mastercard 9' => ['272000', 'mastercard'],
            'diners 1' => ['360000', 'diners'],
            'diners 2' => ['300000', 'diners'],
            'diners 3' => ['305000', 'diners'],
            'diners 4' => ['309500', 'diners'],
            'diners 5' => ['380000', 'diners'],
            'diners 6' => ['390000', 'diners'],
            'jcb 1' => ['352800', 'jcb'],
            'jcb 2' => ['355000', 'jcb'],
            'jcb 3' => ['358900', 'jcb'],
            'mir 1' => ['220000', 'mir'],
            'mir 2' => ['220400', 'mir'],
        ];
    }

    #[DataProvider('banksProvider')]
    public function testBanks(string $prefix, string $expectedTitle): void
    {
        $this->assertSame($expectedTitle, $this->bankDb->getBankInfo($prefix)->getTitle(false));
    }

    public static function banksProvider(): array
    {
        return [
            'Rocketbank' => ['532130', 'Rocketbank'],
            'Alfa-Bank' => ['428906', 'Alfa-Bank'],
        ];
    }

    #[DataProvider('prefixProvider')]
    public function testColor(string $prefix): void
    {
        $this->assertMatchesRegularExpression(
            '/^#[a-f0-9]{6}$/',
            $this->bankDb->getBankInfo($prefix)->getColor()
        );
    }

    public static function prefixProvider(): array
    {
        return [
            'valid prefix' => ['428906'],
            'empty prefix' => [''],
        ];
    }

    public function testCountryCode(): void
    {
        $this->assertSame('ru', $this->bankDb->getBankInfo('428906')->getCountryCode());
    }

    public function testUrl(): void
    {
        $this->assertMatchesRegularExpression(
            '/^https?:\/\//',
            $this->bankDb->getBankInfo('428906')->getUrl()
        );
    }

    public function testDefunct(): void
    {
        $this->assertFalse($this->bankDb->getBankInfo('428906')->isDefunct());
    }
}