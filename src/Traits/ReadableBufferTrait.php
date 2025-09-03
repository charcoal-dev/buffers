<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Traits;

use Charcoal\Buffers\Enums\BufferEncoding;
use Charcoal\Buffers\Support\ByteReader;
use Charcoal\Contracts\Buffers\ReadableBufferInterface;

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
     * @param int $size
     * @param int $offset
     * @param int|null $length
     * @return string
     */
    protected function substr(int $size, int $offset, int $length = null): string
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
    public function encode(BufferEncoding $scheme): string
    {
        return $scheme->encode($this->bytes());
    }
}