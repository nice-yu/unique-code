<?php
declare(strict_types=1);

namespace NiceYu\Tests;

use NiceYu\UniqueCode\UniqueCode;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../src/functions.php';

/**
 * @covers \unique_encode
 * @covers \unique_decode
 * @covers \NiceYu\UniqueCode\UniqueCode
 */
class FunctionsTest extends TestCase
{
    protected function setUp(): void
    {
        UniqueCode::setSeedNumber(0)
            ->setDictionaries([
                '2', '3', '4', '5', '6', '7', '8', '9',
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
                'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
                'S', 'T', 'U', 'V', 'W', 'X'
            ])
            ->setComplement(['Y', 'Z'])
            ->setMax(6);
    }

    public function testUniqueEncode()
    {
        $id = 12345;
        $code = unique_encode($id);
        $this->assertIsString($code);
        $this->assertEquals($id, UniqueCode::decode($code));
    }

    public function testUniqueDecode()
    {
        $id = 12345;
        $code = unique_encode($id);
        $decodedId = unique_decode($code);
        $this->assertEquals($id, $decodedId);
    }
}
