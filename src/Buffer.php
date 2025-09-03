<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers;

use Charcoal\Buffers\Traits\BufferDecodeTrait;
use Charcoal\Buffers\Traits\ReadableBufferTrait;
use Charcoal\Contracts\Buffers\ByteArrayInterface;
use Charcoal\Contracts\Buffers\ReadableBufferInterface;
use Charcoal\Contracts\Buffers\WritableBufferInterface;

/**
 * A final class that represents a mutable buffer, allowing for data manipulation
 * and encoding/decoding of strings or other data types. Implements interfaces
 * for byte array operations, writing, and reading capabilities.
 */
final class Buffer implements
    ByteArrayInterface,
    WritableBufferInterface,
    ReadableBufferInterface
{
    use BufferDecodeTrait;
    use ReadableBufferTrait;

    private string $bytes;
    private int $length;
    private bool $locked = false;
    private int $maxSize = 0;

    /**
     * Creates a new buffer instance with the provided data.
     */
    public function __construct(ReadableBufferInterface|string $data = "")
    {
        $this->setBuffer($data instanceof ReadableBufferInterface ? $data->bytes() : $data);
    }

    /**
     * @return array
     */
    public function __serialize(): array
    {
        return [
            "bytes" => $this->bytes,
            "length" => null,
            "locked" => $this->locked,
            "maxSize" => $this->maxSize,
        ];
    }

    /**
     * @param array $data
     * @return void
     */
    public function __unserialize(array $data): void
    {
        $this->setBuffer($data["bytes"]);
        $this->locked = $data["locked"];
        $this->maxSize = $data["maxSize"];
    }

    /**
     * Sets the buffer data.
     * Enforces maximum size constraints and lock status.
     */
    private function setBuffer(string $data): void
    {
        if ($this->locked) {
            throw new \DomainException("Buffer is locked and cannot be modified");
        }

        if ($this->maxSize > 0 && ($this->length + strlen($data)) > $this->maxSize) {
            throw new \OverflowException(
                sprintf("Buffer has maximum size of %d bytes; Cannot append %d bytes", $this->maxSize, strlen($data)));
        }

        $this->bytes = $data;
        $this->length = strlen($this->bytes);
    }

    /**
     * Returns the current buffer's length
     */
    public function length(): int
    {
        return $this->length;
    }

    /**
     * Append data to the current buffer.
     */
    public function append(ReadableBufferInterface|string $data): self
    {
        $this->setBuffer($this->bytes . ($data instanceof ReadableBufferInterface ? $data->bytes() : $data));
        return $this;
    }

    /**
     * Prepend data to the current buffer.
     */
    public function prepend(ReadableBufferInterface|string $data): self
    {
        $this->setBuffer(($data instanceof ReadableBufferInterface ? $data->bytes() : $data) . $this->bytes);
        return $this;
    }

    /**
     * Returns a new immutable buffer instance with the current buffer's data.
     */
    public function toImmutable(): BufferImmutable
    {
        return new BufferImmutable($this->bytes);
    }

    /**
     * Truncates the buffer to zero lengths
     */
    public function flush(): void
    {
        $this->setBuffer("");
    }

    /**
     * @param int $bytes
     * @return self
     */
    public function setMaxSize(int $bytes): self
    {
        if ($bytes < 0) {
            throw new \InvalidArgumentException("Maximum size must be greater than 0");
        }

        if ($this->length > $bytes) {
            throw new \OverflowException("Buffer size of " . $this->length . " bytes exceeds maximum constraint");
        }

        $this->maxSize = $bytes;
        return $this;
    }

    /**
     * Locks the buffer from any further writing
     */
    public function lock(): self
    {
        $this->locked = true;
        return $this;
    }

    /**
     * Unlocks the buffer for further writing
     */
    public function unlock(): self
    {
        $this->locked = false;
        return $this;
    }

    /**
     * Is this buffer writable?
     */
    public function isLocked(): bool
    {
        return $this->locked;
    }

    /**
     * Creates a new buffer instance with a copy of the current buffer's data.
     */
    public function copy(int $offset = 0, int $length = null): Buffer
    {
        return new Buffer($this->subString($offset, $length));
    }

    /**
     * Reverses a bytes sequence (i.e., endianness) within same instance.
     */
    public function reverse(): self
    {
        $this->setBuffer(strrev($this->bytes));
        return $this;
    }
}

