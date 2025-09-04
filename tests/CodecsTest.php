<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Tests;

use Charcoal\Buffers\Buffer;
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
    public function testByteArrayWithUTF8(): void
    {
        $bA = [217, 129, 216, 177, 217, 130, 216, 167, 217, 134];
        $buffer2 = new Buffer(implode("", array_map(fn($i) => chr($i), $bA)));
        $this->assertNotEquals("فرقا", $buffer2->bytes());
        $this->assertEquals("فرقان", $buffer2->bytes());
    }
}