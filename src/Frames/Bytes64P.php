<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Frames;

/**
 * Class Bytes64P
 * - Use this frame for buffers of length 64 bytes or fewer.
 * - If value is smaller than 64 bytes, it will be padded with NULL bytes to its left.
 * - This class extends Bytes64 so using this in place of Bytes64 type hinting is compatible.
 * @package Charcoal\Buffers\Frames
 */
class Bytes64P extends Bytes64
{
    public const int PAD_TO_LENGTH = STR_PAD_LEFT;
}

