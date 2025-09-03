<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Tests;

use Charcoal\Buffers\Buffer;
use Charcoal\Buffers\Enums\ByteOrder;
use Charcoal\Buffers\Enums\UInt;

/**
 * Class ByteReaderTests
 */
class ByteReaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     */
    public function testByteReader(): void
    {
        $buffer = new Buffer("\0furqan\1\2" . chr(0xfd) . "\3" .
            ByteOrder::LittleEndian->pack32(UInt::Bytes2, 0xfffe) . "\t\r\nsiddiqui"
        );

        $bytes = $buffer->read();
        $this->assertFalse($bytes->isEnd());
        $this->assertEquals("\0fu", $bytes->first(3));
        $this->assertEquals("rq", $bytes->next(2));
        $this->assertEquals("\0f", $bytes->first(2), "Testing reset on 'first' method call");
        $this->assertEquals("urqa", $bytes->lookAhead(4), "Look ahead method test");
        $this->assertEquals("ur", $bytes->next(2), "Making sure pointer was not changed by lookAhead call");
        $bytes->next(3); // ignore next 3 bytes
        $this->assertEquals("furqan", $bytes->lookBehind(6), "Testing look behind method");
        $this->assertEquals("\1\2", $bytes->next(2), "Making sure pointer was not changed by lookBehind call");
        $this->assertEquals(253, $bytes->readUInt8(), "Single byte integer");
        $this->assertEquals("\3", $bytes->next(1));
        $this->assertEquals(65534, $bytes->readUInt16LE(), "Two byte integer");
        $this->assertFalse($bytes->isEnd());
        $this->assertEquals(9, $bytes->readUInt8());
        $this->assertEquals("\t", $bytes->lookBehind(1));
        $bytes->next(2); // ignore next 2 bytes
        $this->assertEquals("siddiqui", $bytes->getRest(), "Using remaining method call will not update pointer");
        $this->assertFalse($bytes->isEnd());
        $bytes->next(8); // ignore next 8 bytes
        $this->assertTrue($bytes->isEnd());
    }

    /**
     * @return void
     */
    public function testUnderflowException(): void
    {
        $bytes = (new Buffer("charcoal.dev"))->read();
        $this->assertEquals("charcoal", $bytes->next(8));
        $this->assertEquals(".", $bytes->next(1));
        $this->expectException('UnderflowException');
        $bytes->next(4); // Attempt to read 4 bytes while only 3 remaining
    }

    /**
     * @return void
     */
    public function testBytesLeft(): void
    {
        $bytes = (new Buffer("charcoal-dev"))->read();
        $bytes->first(4); // skip 4 bytes
        $this->assertEquals(8, $bytes->remaining());
        $bytes->next(4); // skip another 4
        $bytes->readUInt8(); // skip another 1
        $this->assertEquals(3, $bytes->remaining());
        $bytes->next(3);
        $this->assertEquals(0, $bytes->remaining());
    }

    /**
     * @return void
     */
    public function testBytesLeftWithUnderflowEx(): void
    {
        $bytes = (new Buffer("charcoal-dev"))->read();

        $bytes->first(8); // skip 8 bytes
        $this->assertEquals(4, $bytes->remaining());
        $bytes->readUInt16LE(); // skip 2 bytes
        $this->assertEquals(2, $bytes->remaining());
        $this->expectException("UnderflowException");
        $bytes->next(5);
        $this->assertEquals(0, $bytes->remaining());
    }

    /**
     * @return void
     */
    public function testBytesLeft2(): void
    {
        $bytes = (new Buffer("charcoal"))->read();
        $bytes->first(2); // 2
        $bytes->readUInt16LE(); // +2 = 4
        $this->assertEquals("coal", $bytes->next($bytes->remaining()));
        $this->assertTrue($bytes->isEnd());
    }
}