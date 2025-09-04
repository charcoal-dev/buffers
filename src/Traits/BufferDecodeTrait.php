<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Traits;

use Charcoal\Contracts\Buffers\ByteArrayInterface;
use Charcoal\Contracts\Encoding\EncodingSchemeInterface;

/**
 * Provides functionality related to decoding buffer data
 * @mixin ByteArrayInterface
 */
trait BufferDecodeTrait
{
    /**
     *  Instantiate buffer from encoded data
     */
    public static function decode(EncodingSchemeInterface $scheme, string $data): static
    {
        return new static($scheme->decode($data));
    }
}