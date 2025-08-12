<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Frames;

/**
 * Class Bytes20P
 *  - Use this frame for buffers of length 20 bytes or fewer.
 *  - If value is smaller than 20 bytes, it will be padded with NULL bytes to its left.
 *  - This class extends Bytes20 so using this in place of Bytes20 type hinting is compatible.
 * @package Charcoal\Buffers\Frames
 */
class Bytes20P extends Bytes20
{
    public const PAD_TO_LENGTH = STR_PAD_LEFT;
}
