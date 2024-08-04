<?php
declare(strict_types=1);

namespace NiceYu\Tests;

use NiceYu\UniqueCode\UniqueCode;
use PHPUnit\Framework\TestCase;

/**
 * @covers \NiceYu\UniqueCode\UniqueCode
 */
class UniqueCodeTest extends TestCase
{
    protected function setUp(): void
    {
        UniqueCode::setSeedNumber(123456789)
            ->setDictionaries([
                '2', '3', '4', '5', '6', '7', '8', '9',
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
                'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
                'S', 'T', 'U', 'V', 'W', 'X'
            ])
            ->setComplement(['Y', 'Z'])
            ->setMax(6);
    }

    public function testGetters()
    {
        $this->assertEquals(123456789, UniqueCode::getSeedNumber());
        $this->assertEquals([
            '2', '3', '4', '5', '6', '7', '8', '9',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
            'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
            'S', 'T', 'U', 'V', 'W', 'X'
        ], UniqueCode::getDictionaries());
        $this->assertEquals(['Y', 'Z'], UniqueCode::getComplement());
        $this->assertEquals(6, UniqueCode::getMax());
    }

    public function testEncodeDecodeConsistency()
    {
        $id = 12345;
        $code = UniqueCode::encode($id);
        $decodedId = UniqueCode::decode($code);

        $this->assertEquals($id, $decodedId);
    }

    public function testEncodeWithMaxLength()
    {
        $id = 123456;
        $code = UniqueCode::encode($id);
        $this->assertNotNull($code);
        $this->assertEquals(6, strlen($code));
    }

    public function testEncodeExceedingMax()
    {
        $id = 1000000000; // This value exceeds the limit for 6-character code
        $code = UniqueCode::encode($id);
        $this->assertNull($code);
    }

    public function testInvalidDecode()
    {
        $invalidCode = "ZZZZZZ";
        $decodedId = UniqueCode::decode($invalidCode);
        $this->assertEquals(0, $decodedId);
    }

    public function testSetSeedNumber()
    {
        $unique = UniqueCode::setSeedNumber(987654321);
        $this->assertInstanceOf(UniqueCode::class, $unique);
        $this->assertEquals(987654321, UniqueCode::getSeedNumber());
    }

    public function testSetDictionaries()
    {
        $dictionaries = ['A', 'B', 'C', 'D', 'E', 'F'];
        $unique = UniqueCode::setDictionaries($dictionaries);
        $this->assertInstanceOf(UniqueCode::class, $unique);
        $this->assertEquals($dictionaries, UniqueCode::getDictionaries());
    }

    public function testSetComplement()
    {
        $complement = ['X', 'Y'];
        $unique = UniqueCode::setComplement($complement);
        $this->assertInstanceOf(UniqueCode::class, $unique);
        $this->assertEquals($complement, UniqueCode::getComplement());
    }

    public function testSetMax()
    {
        $unique = UniqueCode::setMax(8);
        $this->assertInstanceOf(UniqueCode::class, $unique);
        $this->assertEquals(8, UniqueCode::getMax());
    }
}
