<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Codecs\TLV\Types;

use Charcoal\Buffers\Contracts\Codecs\TLV\ParamTypeEnumInterface;
use Charcoal\Buffers\Contracts\Codecs\TLV\ProtocolEnumInterface;
use Charcoal\Buffers\Enums\ByteOrder;
use Charcoal\Buffers\Enums\UInt;

/**
 * Standard concrete Enum for common TLV parameter types.
 */
enum TlvParamType: int implements ParamTypeEnumInterface
{
    case Null = 0;
    case UInt8 = 1;
    case UInt16 = 2;
    case UInt32 = 3;
    case UInt64 = 4;
    case Bool = 5;
    case String = 6;

    /**
     * @param mixed $value
     * @param ProtocolEnumInterface $context
     * @return string
     */
    public function encode(mixed $value, ProtocolEnumInterface $context): string
    {
        $byteOrder = $context->getByteOrder();
        return match ($this) {
            self::Null => "",
            self::UInt8 => chr((int)$value),
            self::UInt16 => pack($byteOrder === ByteOrder::BigEndian ? "n" : "v", (int)$value),
            self::UInt32 => pack($byteOrder === ByteOrder::BigEndian ? "N" : "V", (int)$value),
            self::UInt64 => pack("J", (int)$value),
            self::Bool => $value ? "\x01" : "\x00",
            self::String => (string)$value,
        };
    }

    /**
     * @param string|null $encoded
     * @param ProtocolEnumInterface $context
     * @return mixed
     */
    public function decode(?string $encoded, ProtocolEnumInterface $context): mixed
    {
        if ($encoded === null || $encoded === "") {
            return match ($this) {
                self::Null => null,
                self::String => "",
                self::Bool => false,
                default => 0,
            };
        }

        $byteOrder = $context->getByteOrder();
        return match ($this) {
            self::Null => null,
            self::UInt8 => ord($encoded),
            self::UInt16 => unpack($byteOrder === ByteOrder::BigEndian ? "n" : "v", $encoded)[1],
            self::UInt32 => unpack($byteOrder === ByteOrder::BigEndian ? "N" : "V", $encoded)[1],
            self::UInt64 => unpack("J", $encoded)[1],
            self::Bool => $encoded !== "\x00",
            self::String => $encoded,
        };
    }

    /**
     * @return \Charcoal\Buffers\Enums\UInt
     */
    public function getLengthBits(): UInt
    {
        return match ($this) {
            self::String => UInt::Bytes2,
            default => UInt::Byte,
        };
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->value;
    }
}