<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Contracts\Codecs\TLV;

/**
 * Represents the contract for defining a set of related enumerated values
 * associated with a frame code, extending the base PHP UnitEnum functionality.
 */
interface FrameCodeEnumInterface extends \UnitEnum
{
    public function getId(): int;
}