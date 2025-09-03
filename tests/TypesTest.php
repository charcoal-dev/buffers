<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Tests;

use Charcoal\Buffers\Types\Bytes16;
use Charcoal\Buffers\Types\Bytes20;
use Charcoal\Buffers\Types\Bytes32;

/**
 * Class TypesTest
 * @package Charcoal\Buffers\Tests
 */
class TypesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Basic check
     * @return void
     */
    public function testFrameFixLength(): void
    {
        $hash = hash("sha256", "furqansiddiqui", true);
        $frame = new Bytes32($hash);
        $this->assertEquals(32, $frame->length());
    }

    /**
     * Attempt to give a smaller length value to Bytes20P frame
     * @return void
     */
    public function testFramePaddedLength(): void
    {
        $frame = Bytes20::setPadded("furqansiddiqui");
        $this->assertEquals(20, $frame->length());
        $this->assertEquals("\0\0\0\0\0\0furqansiddiqui", $frame->bytes());
    }

    /**
     * Attempt to give 32 bytes value to a 20-byte frame
     * @return void
     */
    public function testFrameOverflow(): void
    {
        $this->expectException("LengthException");
        new Bytes20(hash("sha256", "furqansiddiqui", true));
    }

    /**
     * Attempt to give 32 bytes value to a 20 byte padded frame
     * @return void
     */
    public function testPaddedFrameOverflow(): void
    {
        $this->expectException('LengthException');
        Bytes20::setPadded(hash("sha256", "furqansiddiqui", true));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFromRandom(): void
    {
        $b16 = Bytes16::fromPrng();
        $this->assertEquals(16, $b16->length());
        $b20 = Bytes20::fromPrng();
        $this->assertEquals(20, $b20->length());
        $b32 = Bytes32::fromPrng();
        $this->assertEquals(32, $b32->length());
    }
}