<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Support;

/**
 * Provides utilities for working with endianness in computing systems.
 * Endianness refers to the order in which bytes are arranged within a binary
 * representation of data, which can be either little-endian (least-significant
 * byte first) or big-endian (most-significant byte first).
 */
final class Endianness
{
    protected static ?bool $machineIsLittleEndian = null;

    /**
     * Reverses the byte order of a binary string
     */
    public static function swap(string $bn): string
    {
        return strrev($bn);
    }

    /**
     * Determines if the machine's architecture follows little-endian byte order.
     */
    public static function isLittleEndian(): bool
    {
        if (!is_bool(self::$machineIsLittleEndian)) {
            self::$machineIsLittleEndian = pack("S", 1) === pack("v", 1);
        }

        return self::$machineIsLittleEndian;
    }

    /**
     * Determines if the machine's architecture follows big-endian byte order.
     */
    public static function isBigEndian(): bool
    {
        return !self::isLittleEndian();
    }
}