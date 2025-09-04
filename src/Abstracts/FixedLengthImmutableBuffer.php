<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Abstracts;

use Charcoal\Buffers\Traits\BufferDecodeTrait;
use Charcoal\Buffers\Traits\ReadableBufferTrait;
use Charcoal\Contracts\Buffers\Immutable\FixedLengthBufferInterface;
use Charcoal\Contracts\Buffers\Immutable\ImmutableBufferInterface;
use Charcoal\Contracts\Buffers\ReadableBufferInterface;
use Random\RandomException;

/**
 * Represents an immutable buffer with a fixed length.
 * This class provides functionality to manage and manipulate
 * the buffer in an immutable way while ensuring the buffer's
 * length remains constant.
 */
readonly class FixedLengthImmutableBuffer implements
    ImmutableBufferInterface,
    FixedLengthBufferInterface,
    ReadableBufferInterface
{
    use ReadableBufferTrait;
    use BufferDecodeTrait;

    protected const int FixedLengthBytes = 0;

    private int $length;

    /**
     * Returns a new instance with the seed padded to the fixed length.
     */
    public static function setPadded(string $seed): static
    {
        return new static(str_pad($seed, static::FixedLengthBytes, "\0", STR_PAD_LEFT));
    }

    /**
     * @return static
     */
    public static function fromPrng(): static
    {
        try {
            return new static(random_bytes(static::FixedLengthBytes));
        } catch (RandomException $e) {
            throw new \RuntimeException(
                sprintf("Failed to source %d bytes from cryptographically-secure PRNG method",
                    static::FixedLengthBytes),
                previous: $e
            );
        }
    }

    /**
     * Constructor enforces fixed length.
     */
    final public function __construct(private string $bytes)
    {
        if (static::FixedLengthBytes <= 0) {
            throw new \InvalidArgumentException(static::class . ": Fixed length must be greater than 0");
        }

        $this->length = strlen($bytes);
        if ($this->length !== static::FixedLengthBytes) {
            throw new \LengthException(
                sprintf("%s: seed must be exactly %d bytes, got %d",
                    static::class, static::FixedLengthBytes, strlen($bytes)));
        }
    }

    /**
     * @return array
     */
    public function __serialize(): array
    {
        return [
            "bytes" => $this->bytes,
        ];
    }

    /**
     * @param array $data
     * @return void
     */
    public function __unserialize(array $data): void
    {
        $this->bytes = $data["bytes"];
        $this->length = strlen($this->bytes);
    }

    /**
     * Returns true if the buffer is padded.
     */
    public function isPadded(): bool
    {
        return str_starts_with($this->bytes, "\0");
    }

    /**
     * Returns true if the buffer is empty.
     */
    public function isEmpty(): bool
    {
        return ltrim($this->bytes, "\0") === "";
    }

    /**
     * Returns the buffer's content, trimmed if requested.
     */
    public function getClean(bool $trimLeft = true, bool $trimRight = true): string
    {
        return match (true) {
            $trimLeft && $trimRight => trim($this->bytes),
            $trimLeft => ltrim($this->bytes),
            $trimRight => rtrim($this->bytes),
            default => $this->bytes,
        };
    }
}