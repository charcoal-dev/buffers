<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Frames;

use Charcoal\Buffers\AbstractFixedLenBuffer;

/**
 * Class Bytes20
 * - Use this frame for buffers of precisely 20 bytes.
 * - If value is smaller than 20 bytes, LengthException will be thrown.
 * @package Charcoal\Buffers\Frames
 */
class Bytes20 extends AbstractFixedLenBuffer
{
    public const int SIZE = 20; // Fixed frame-size of 20 bytes
    protected const ?int PAD_TO_LENGTH = null; // No padding, constructor argument MUST be precisely 20 bytes

    use CompareSmallFramesTrait;
}