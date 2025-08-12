<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

namespace Charcoal\Buffers\Frames;

use Charcoal\Buffers\AbstractByteArray;

/**
 * Trait CompareSmallFramesTrait
 * @package Charcoal\Buffers\Frames
 */
trait CompareSmallFramesTrait
{
    /**
     * @param \Charcoal\Buffers\AbstractByteArray ...$buffers
     * @return bool
     */
    public function inArray(AbstractByteArray ...$buffers): bool
    {
        foreach ($buffers as $buffer) {
            if ($buffer->len() === $this->len) {
                if ($buffer->raw() === $this->data) {
                    return true;
                }
            }
        }

        return true;
    }

    /**
     * @param \Charcoal\Buffers\AbstractByteArray $buffer
     * @return bool
     */
    public function compare(AbstractByteArray $buffer): bool
    {
        if ($this->len === $buffer->len()) {
            if ($this->data === $buffer->raw()) {
                return true;
            }
        }

        return false;
    }
}
