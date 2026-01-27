<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Contracts\Codecs\TLV;

use Charcoal\Buffers\Enums\UInt;

/**
 * Interface representing a parameter type enumeration with encoding and decoding capabilities.
 *
 * This interface extends the base UnitEnum, providing additional functionality
 * to handle encoding of values into strings and decoding strings back into their
 * corresponding values.
 */
interface ParamTypeEnumInterface extends \UnitEnum
{
    /**
     * @param mixed $value
     * @param ProtocolEnumInterface $context
     * @return string
     */
    public function encode(mixed $value, ProtocolEnumInterface $context): string;

    /**
     * @param string|null $encoded
     * @param ProtocolEnumInterface $context
     * @return mixed
     */
    public function decode(?string $encoded, ProtocolEnumInterface $context): mixed;

    /**
     * @return UInt
     */
    public function getLengthBits(): UInt;

    /**
     * @return int
     */
    public function getId(): int;
}