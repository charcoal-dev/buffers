<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Types;

use Charcoal\Buffers\Abstracts\FixedLengthImmutableBuffer;

/**
 * Type for a 32-byte (Immutable and Fixed-Length) buffer.
 */
final readonly class Bytes32 extends FixedLengthImmutableBuffer
{
    protected const int FixedLengthBytes = 32;
}