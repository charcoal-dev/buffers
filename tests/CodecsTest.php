<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Tests;

use Charcoal\Buffers\Buffer;
use Charcoal\Buffers\Enums\BufferEncoding;
use Charcoal\Buffers\Types\Bytes32;
use Charcoal\Contracts\Buffers\ByteArrayInterface;

/**
 * Class CodecsTest
 */
class CodecsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     */
    public function testSerialize(): void
    {
        $buffer = new Buffer("charcoal");
        $ser1 = serialize($buffer);

        /** @var ByteArrayInterface $restored1 */
        $restored1 = unserialize($ser1);

        $this->assertInstanceOf(Buffer::class, $restored1);
        $this->assertEquals(8, $restored1->length());
        $this->assertEquals("charcoal", $restored1->bytes());
    }

    /**
     * @return void
     */
    public function testBase16(): void
    {
        $raw = hash("sha256", "furqansiddiqui", true);
        $hex = bin2hex($raw);

        $frame1 = Bytes32::fromBase16("0x" . $hex);
        $frame2 = Bytes32::fromBase16($hex);

        $this->assertTrue($frame1->equals($frame2), "Testing that 0x prefix is discarded");
        $this->assertEquals($frame1->encode(BufferEncoding::Base16), $hex, "Compare hex strings");
        $this->assertEquals($frame1->bytes(), $raw, "Compare raw strings");
    }

    /**
     * @return void
     */
    public function testByteArrayWithUTF8(): void
    {
        $bA = [217, 129, 216, 177, 217, 130, 216, 167, 217, 134];
        $buffer2 = new Buffer(implode("", array_map(fn($i) => chr($i), $bA)));
        $this->assertNotEquals("فرقا", $buffer2->bytes());
        $this->assertEquals("فرقان", $buffer2->bytes());
    }

    /**
     * @return void
     */
    public function testBase64(): void
    {
        $string = "YjY0AHRlc3Q=";
        $buffer = Buffer::fromBase64($string);
        $this->assertNotEquals("b64 test", $buffer->bytes());
        $this->assertEquals("b64\0test", $buffer->bytes());
        $this->assertEquals($string, $buffer->encode(BufferEncoding::Base64));
    }
}