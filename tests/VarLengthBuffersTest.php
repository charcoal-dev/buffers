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
        $this->assertTrue((new Buffer(""))->isWritable());
    }

    /**
     * @return void
     */
    public function testReadOnly(): void
    {
        $buffer = new Buffer("charcoal");
        $buffer->readOnly();

        $this->expectException('BadMethodCallException');
        $buffer->append(".dev");
    }

    /**
     * @return void
     */
    public function testWritable(): void
    {
        $buffer = new Buffer("charcoal");
        $buffer->writable();
        $buffer->append(".dev");
        $this->assertEquals("charcoal.dev", $buffer->raw());
    }

    /**
     * @return void
     */
    public function testAppendPrepend(): void
    {
        $buffer = new Buffer("coal");
        $buffer->prepend(Buffer::fromByteArray([0x63, 0x68, 0x61, 0x72]));
        $buffer->append(".dev");
        $this->assertEquals("charcoal.dev", $buffer->raw());
    }

    /**
     * @return void
     */
    public function testFlush(): void
    {
        $buffer = new Buffer("abcd");
        $buffer->flush(); // Flush
        $this->assertEquals(0, $buffer->len());
    }

    /**
     * @return void
     */
    public function testFlushInReadOnlyMode(): void
    {
        $buffer = new Buffer("abcd");
        $buffer->readOnly();
        $this->expectException('BadMethodCallException');
        $buffer->flush(); // Flush
    }
}