<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Support;

use Charcoal\Contracts\Buffers\ReadableBufferInterface;

/**
 * A utility class for reading bytes from a buffer. Provides functionality to navigate,
 * read, and manipulate byte positions within a given buffer while supporting both
 * little-endian and big-endian formats for numerical representation.
 */
class ByteReader
{
    private readonly string $buffer;
    public readonly int $size;
    private int $pointer = 0;

    public function __construct(ReadableBufferInterface|string $buffer)
    {
        $this->buffer = $buffer instanceof ReadableBufferInterface ? $buffer->bytes() : $buffer;
        $this->size = strlen($this->buffer);
    }

    /**
     * End of buffer?
     */
    public function isEnd(): bool
    {
        return $this->pointer >= $this->size;
    }

    /**
     * Current pointer position in buffer
     */
    public function pos(): int
    {
        return $this->pointer;
    }

    /**
     * Start reading from beginning
     */
    public function reset(): self
    {
        $this->pointer = 0;
        return $this;
    }

    /**
     * Resets pointer, gets next N bytes from top
     */
    public function first(int $bytes): string
    {
        return $this->reset()->next($bytes);
    }

    /**
     * Reads last N bytes, previously read (does NOT update an internal pointer)
     */
    public function lookBehind(int $bytes): string
    {
        $goBack = $this->pointer - $bytes;
        if ($bytes < 1 || $goBack < 0) {
            throw new \InvalidArgumentException('Expected positive number of bytes to read');
        }


        return substr($this->buffer, $goBack, $bytes);
    }

    /**
     * Reads next N bytes, but does NOT update an internal pointer
     */
    public function lookAhead(int $bytes): string
    {
        if ($bytes < 1) {
            throw new \InvalidArgumentException('Expected positive number of bytes to read');
        }

        return substr($this->buffer, $this->pointer, $bytes);
    }

    /**
     * Reads next N bytes while updating the pointer (+n)
     */
    public function next(int $bytes): string
    {
        if (($this->pointer + $bytes) > $this->size) {
            throw new \UnderflowException(sprintf(
                'Attempt to read next %d bytes, while only %d available',
                $bytes,
                ($this->size - $this->pointer)
            ));
        }

        $read = $this->lookAhead($bytes);
        if (strlen($read) === $bytes) {
            $this->pointer += $bytes;
            return $read;
        }

        throw new \UnderflowException(sprintf('ByteReader ran out of bytes at pos %d', $this->pointer));
    }

    /**
     * Reads next byte as an unsigned integer,
     * Updates the pointer (+1)
     */
    public function readUInt8(): int
    {
        return ord($this->next(1));
    }

    /**
     * Reads next 2 bytes as an unsigned integer (little-ending byte order),
     * Updates the pointer (+2)
     */
    public function readUInt16LE(): int
    {
        return unpack("v", $this->next(2))[1];
    }

    /**
     * Reads next 2 bytes as an unsigned integer (big-ending byte order),
     * Updates the pointer (+2)
     */
    public function readUInt16BE(): int
    {
        return unpack("n", $this->next(2))[1];
    }

    /**
     * Reads next 4 bytes as an unsigned integer (little-ending byte order),
     * Updates the pointer (+4)
     */
    public function readUInt32LE(): int
    {
        return unpack("V", $this->next(4))[1];
    }

    /**
     * Reads next 4 bytes as an unsigned integer (big-ending byte order),
     * Updates the pointer (+4)
     */
    public function readUInt32BE(): int
    {
        return unpack("N", $this->next(4))[1];
    }

    /**
     * Reads next 8 bytes, to be interpreted as an unsigned integer,
     * use GMP or Charcoal GMP adapter to get int
     * Updates the pointer (+8)
     */
    public function readUInt64(): string
    {
        return $this->next(8);
    }

    /**
     * Manually set the pointer at specific position
     */
    public function setPointer(int $pos): self
    {
        if ($pos < 0 || $pos > $this->size) {
            throw new \RangeException("Invalid pointer position or is out of range");
        }

        $this->pointer = $pos;
        return $this;
    }

    /**
     * Returns number of bytes remaining in buffer
     */
    public function remaining(): int
    {
        return $this->size - $this->pointer;
    }

    /**
     * Returns the rest of the buffer, starting from the current pointer position
     * Does not update the pointer
     */
    public function getRest(): string
    {
        return substr($this->buffer, $this->pointer);
    }
}