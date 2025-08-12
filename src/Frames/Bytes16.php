<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Frames;

use Charcoal\Buffers\AbstractFixedLenBuffer;

/**
 * Class Bytes16
 * - Use this frame for buffers of precisely 16 bytes.
 * - If value is smaller than 16 bytes, LengthException will be thrown.
 * @package Charcoal\Buffers\Frames
 */
class Bytes16 extends AbstractFixedLenBuffer
{
    public const int SIZE = 16; // Fixed frame-size of 16 bytes
    protected const ?int PAD_TO_LENGTH = null; // No padding, constructor argument MUST be precisely 16 bytes

    use CompareSmallFramesTrait;
}
