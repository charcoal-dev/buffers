<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Frames;

/**
 * Class Bytes32P
 *  - Use this frame for buffers of length 32 bytes or fewer.
 *  - If value is smaller than 32 bytes, it will be padded with NULL bytes to its left.
 *  - This class extends Bytes32 so using this in place of Bytes32 type hinting is compatible.
 * @package Charcoal\Buffers\Frames
 * @deprecated
 */
class Bytes32P extends Bytes32
{
    public const int PAD_TO_LENGTH = STR_PAD_LEFT;
}

