<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Abstracts;

use Charcoal\Buffers\Traits\BufferDecodeTrait;
use Charcoal\Buffers\Traits\ReadableBufferTrait;
use Charcoal\Contracts\Buffers\FixedLengthBufferInterface;
use Charcoal\Contracts\Buffers\ImmutableBufferInterface;
use Charcoal\Contracts\Buffers\ReadableBufferInterface;

/**
 * Represents an immutable buffer with a fixed length.
 * This class provides functionality to manage and manipulate
 * the buffer in an immutable way while ensuring the buffer's
 * length remains constant.
 */
readonly class FixedLenImmutableBuffer implements
    ImmutableBufferInterface,
    FixedLengthBufferInterface,
    ReadableBufferInterface
{
    use ReadableBufferTrait;
    use BufferDecodeTrait;

    private int $length;

    protected function __construct(private string $bytes)
    {
    }

    /**
     * Returns resulting starting after applying offset and length.
     */
    public function substr(int $offset, int $length = null): string
    {
        return $this->_substr($this->length, $offset, $length);
    }
}