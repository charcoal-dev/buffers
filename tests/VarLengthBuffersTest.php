<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Tests;

use Charcoal\Buffers\Buffer;

/**
 * Class VarLengthBuffersTest
 */
class VarLengthBuffersTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     */
    public function testNewBufferIsWritable(): void
    {
        $this->assertFalse((new Buffer(""))->isLocked());
    }

    /**
     * @return void
     */
    public function testReadOnly(): void
    {
        $buffer = new Buffer("charcoal");
        $buffer->lock();

        $this->expectException("DomainException");
        $buffer->append(".dev");
    }

    /**
     * @return void
     */
    public function testWritable(): void
    {
        $buffer = new Buffer("charcoal");
        $buffer->unlock();
        $buffer->append(".dev");
        $this->assertEquals("charcoal.dev", $buffer->bytes());
    }

    /**
     * @return void
     */
    public function testAppendPrepend(): void
    {
        $buffer = new Buffer("coal");
        $buffer->prepend(new Buffer("\x63\x68\x61\x72"));
        $buffer->append(".dev");
        $this->assertEquals("charcoal.dev", $buffer->bytes());
    }

    /**
     * @return void
     */
    public function testFlush(): void
    {
        $buffer = new Buffer("abcd");
        $buffer->flush(); // Flush
        $this->assertEquals(0, $buffer->length());
    }

    /**
     * @return void
     */
    public function testFlushInReadOnlyMode(): void
    {
        $buffer = new Buffer("abcd");
        $buffer->lock();
        $this->expectException("DomainException");
        $buffer->flush(); // Flush
    }
}