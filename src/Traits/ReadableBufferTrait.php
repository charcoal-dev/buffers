<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Traits;

use Charcoal\Buffers\Support\ByteReader;
use Charcoal\Contracts\Buffers\ReadableBufferInterface;
use Charcoal\Contracts\Encoding\EncodingSchemeInterface;

/**
 * Provides functionality related to readable buffer operations
 * @mixin ReadableBufferInterface
 */
trait ReadableBufferTrait
{
    /**
     * @return string
     */
    public function bytes(): string
    {
        return $this->bytes;
    }

    /**
     * @return int
     */
    public function length(): int
    {
        return $this->length;
    }

    /**
     * @param int $size
     * @param int $offset
     * @param int|null $length
     * @return string
     */
    protected function _substr(int $size, int $offset, int $length = null): string
    {
        $off = max(-$size, min($offset, $size));
        if ($length === null) {
            return substr($this->bytes, $off) ?: "";
        }

        if ($length >= 0) {
            return substr($this->bytes, $off, $length) ?: "";
        }

        return substr($this->bytes, $off, -min($size, max(0, -$length))) ?: "";
    }

    /**
     * Returns a new byte reader instance.
     */
    public function read(): ByteReader
    {
        return new ByteReader($this);
    }

    /**
     * Returns the encoded string.
     */
    public function encode(EncodingSchemeInterface $scheme): string
    {
        return $scheme->encode($this->bytes);
    }

    /**
     * Compares the buffer with another buffer or string.
     */
    public function equals(string|ReadableBufferInterface $b): bool
    {
        return hash_equals($this->bytes, $b instanceof ReadableBufferInterface ? $b->bytes() : $b);
    }

    /**
     * Checks if the current string contains the given substring.
     */
    public function contains(string $needle): bool
    {
        return str_contains($this->bytes, $needle);
    }

    /**
     * Checks if the current string starts with the given substring.
     */
    public function startsWith(string $needle): bool
    {
        return str_starts_with($this->bytes, $needle);
    }

    /**
     * Checks if the current string ends with the given substring.
     */
    public function endsWith(string $needle): bool
    {
        return str_ends_with($this->bytes, $needle);
    }
}