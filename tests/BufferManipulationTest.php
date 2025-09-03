<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Tests;

use Charcoal\Buffers\Buffer;
use Charcoal\Buffers\BufferImmutable;
use Charcoal\Buffers\Enums\BufferEncoding;

/**
 * Class BufferManipulationTest
 */
class BufferManipulationTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @return void
     */
    public function testBufferFromMultipleCodes(): void
    {
        $buffer = new Buffer(); // Blank buffer
        $buffer->append(Buffer::fromBase16("63686172"));
        $buffer->append(new BufferImmutable("\x63\x6f\x61\x6c"));
        $this->assertEquals("charcoal", $buffer->bytes());
    }

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

    /**
     * @return void
     */
    public function testSwitchEndiannessMethod(): void
    {
        $buffer = Buffer::fromBase16("a1b2c3");
        $switched = $buffer->reverse();
        $this->assertEquals("c3b2a1", $switched->encode(BufferEncoding::Base16));
    }
}