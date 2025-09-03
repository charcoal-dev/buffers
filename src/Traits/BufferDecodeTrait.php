<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Traits;

use Charcoal\Buffers\Enums\BufferEncoding;
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

    /**
     * Instantiate buffer from Base64 encoded data
     */
    public static function fromBase16(string $data): static
    {
        return new static(BufferEncoding::decodeBase16($data));
    }

    /**
     * Instantiate buffer from Base64 encoded data
     */
    public static function fromBase64(string $data): static
    {
        return new static(BufferEncoding::decodeBase64($data));
    }
}