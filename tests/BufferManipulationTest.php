<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Tests;

use Charcoal\Buffers\Buffer;
use Charcoal\Buffers\Enums\ByteOrder;

/**
 * Class BufferManipulationTest
 */
class BufferManipulationTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @return void
     */
    public function testPopMethod(): void
    {
        $buffer = new Buffer("charcoal");
        $this->assertEquals("ch", $buffer->subString(0, 2)); // Pop 2 bytes, don't change buffer
        $this->assertEquals("charcoal", $buffer->bytes()); // Buffer was not altered
    }

    /**
     * @return void
     */
    public function testCopyAndEqualMethods(): void
    {
        $buffer1 = new Buffer("charcoal");

        $this->assertEquals("charcoal", $buffer1->copy()->bytes(), "Simple Copy");
        $this->assertEquals("char", $buffer1->copy(0, 4)->bytes(), "Copy initial 4 bytes as new Buffer");
        $this->assertEquals("charcoal", $buffer1->bytes(), "Original buffer is intact");
        $this->assertEquals("harc", $buffer1->copy(1, 4)->bytes(), "Get 4 bytes starting after first byte");
        $this->assertEquals("coal", $buffer1->copy(-4)->bytes(), "Copy last 4 bytes as new Buffer");
        $this->assertEquals("coa", $buffer1->copy(-4, 3)->bytes(), "Copy 3 bytes from set of last 4 bytes");
        $this->assertEquals("arcoal", $buffer1->copy(2)->bytes(), "Copy all bytes as new Buffer except first 2");

        $coal = new Buffer("coal");
        $this->assertTrue($buffer1->copy(-4)->equals($coal));
        $this->assertFalse($buffer1->copy(-4)->equals(new Buffer("cola")));
    }

    public function testWriteUIntMethods(): void
    {
        $buffer = new Buffer();
        $buffer->writeUInt8(0x01);
        $buffer->writeUInt16(0x0102, ByteOrder::LittleEndian);
        $buffer->writeUInt16(0x0304, ByteOrder::BigEndian);
        $buffer->writeUInt32(0x05060708, ByteOrder::LittleEndian);
        $buffer->writeUInt32(0x090A0B0C, ByteOrder::BigEndian);

        $expected = "\x01" .                // UInt8: 1
            "\x02\x01" .                    // UInt16LE: 0x0102
            "\x03\x04" .                    // UInt16BE: 0x0304
            "\x08\x07\x06\x05" .            // UInt32LE: 0x05060708
            "\x09\x0A\x0B\x0C";            // UInt32BE: 0x090A0B0C

        $this->assertEquals($expected, $buffer->bytes());
    }
}