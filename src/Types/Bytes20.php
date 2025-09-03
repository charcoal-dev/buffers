<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Types;

use Charcoal\Buffers\Abstracts\FixedLengthImmutableBuffer;

/**
 * Type for 20-byte (Immutable + Fixed-Length) buffer.
 */
final readonly class Bytes20 extends FixedLengthImmutableBuffer
{
    protected const int FixedLengthBytes = 20;
}