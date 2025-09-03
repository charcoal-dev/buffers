<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Traits;

use Charcoal\Buffers\Enums\BufferEncoding;
use Charcoal\Contracts\Buffers\ByteArrayInterface;

/**
 * Provides functionality related to decoding buffer data
 * @mixin ByteArrayInterface
 */
trait BufferDecodeTrait
{
    /**
     * @param BufferEncoding $scheme
     * @param string $data
     * @return static
     */
    public static function decode(BufferEncoding $scheme, string $data): static
    {
        return new static($scheme->decode($data));
    }
}