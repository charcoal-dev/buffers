<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

namespace Charcoal\Buffers\Frames;

use Charcoal\Buffers\AbstractFixedLenBuffer;

/**
 * Class Bytes64
 * - Use this frame for buffers of precisely 64 bytes.
 * - If value is smaller than 64 bytes, LengthException will be thrown.
 * @package Charcoal\Buffers\Frames
 * @deprecated
 */
class Bytes64 extends AbstractFixedLenBuffer
{
    public const int SIZE = 64; // Fixed frame-size of 64 bytes
    protected const ?int PAD_TO_LENGTH = null; // No padding, constructor argument MUST be precisely 64 bytes

    use CompareSmallFramesTrait;
}
