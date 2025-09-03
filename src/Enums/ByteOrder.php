<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Enums;

/**
 * An enum representing byte order (endianness) for packing and unpacking integers.
 */
enum ByteOrder
{
    case LittleEndian;
    case BigEndian;

    /**
     * Packs an 8-bit to 32-bit integers into a string.
     */
    public function pack32(UInt $bytes, int $n): string
    {
        if ($n < 0) {
            throw new \UnderflowException("Unsigned integer cannot be packed");
        }

        return match (true) {
            ($bytes->value === 8 && $n <= 255) => chr($n),
            ($bytes->value === 16 && $n <= 65535) => pack($this === self::BigEndian ? "n" : "v", $n),
            ($bytes->value === 32 && $n <= 4294967295) => pack($this === self::BigEndian ? "N" : "V", $n),
            default => throw new \OverflowException("Integer cannot be packed as " . $bytes->name),
        };
    }

    /**
     * Unpacks an 8-bit to 32-bit integers from a string.
     */
    public function unpack32(string $bn, UInt $bytes): int
    {
        if ((strlen($bn) * 8) !== $bytes->value) {
            throw new \LengthException("Input must be " . ($bytes->value / 8) . " bytes long");
        }

        return match (true) {
            ($bytes->value === 8) => ord($bn),
            ($bytes->value === 16) => unpack($this === self::BigEndian ? "n" : "v", $bn)[1],
            ($bytes->value === 32) => unpack($this === self::BigEndian ? "N" : "V", $bn)[1],
            default => throw new \OverflowException("Integer cannot be unpacked as " . $bytes->name),
        };
    }
}