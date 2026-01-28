<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Contracts\Codecs\TLV;

use Charcoal\Buffers\Enums\ByteOrder;

/**
 * An interface defining the structure for an envelope type, including
 * its unique identifier, encoding properties, and capacity limits.
 */
interface ProtocolEnumInterface extends \UnitEnum
{
    /**
     * Unique identifier for the envelope type.
     */
    public function getId(): int;

    /**
     * Byte order for encoding of the TLV items.
     */
    public function getByteOrder(): ByteOrder;

    /**
     * Maximum number of frames per envelope.
     */
    public function getFramesCap(): int;

    /**
     * Maximum number of parameters per frame.
     */
    public function getParamsCap(): int;

    /**
     * Maximum length of a parameter value.
     */
    public function getMaxLength(): int;

    public function allowTrailingBytes(): bool;
}