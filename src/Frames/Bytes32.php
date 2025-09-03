<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Frames;

use Charcoal\Buffers\AbstractFixedLenBuffer;

/**
 * Class Bytes32
 * - Use this frame for buffers of precisely 32 bytes.
 * - If value is smaller than 32 bytes, LengthException will be thrown.
 * @package Charcoal\Buffers\Frames
 * @deprecated
 */
class Bytes32 extends AbstractFixedLenBuffer
{
    public const int SIZE = 32; // Fixed frame-size of 32 bytes
    protected const ?int PAD_TO_LENGTH = null; // No padding, constructor argument MUST be precisely 32 bytes

    use CompareSmallFramesTrait;
}