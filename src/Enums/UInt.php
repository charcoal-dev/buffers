<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Enums;

/**
 * Enum UInt represents an unsigned integer type categorized by size in bytes.
 * It provides constants and methods to determine the size of an integer and
 * validate if it fits within a specific size constraint.
 */
enum UInt: int
{
    case Byte = 8;
    case Bytes2 = 16;
    case Bytes4 = 32;
    case Bytes8 = 64;

    /**
     * Returns the size of an integer in bytes.
     */
    public static function getSize(int|string $n): ?self
    {
        if (is_int($n)) {
            return match (true) {
                $n < 0 => null,
                $n <= 0xFF => self::Byte,
                $n <= 0xFFFF => self::Bytes2,
                $n <= 0xFFFFFFFF => self::Bytes4,
                default => self::Bytes8,
            };
        }

        if (is_string($n) && ctype_digit($n)) {
            return match (true) {
                self::leqDec($n, "255") => self::Byte,
                self::leqDec($n, "65535") => self::Bytes2,
                self::leqDec($n, "4294967295") => self::Bytes4,
                self::leqDec($n, "18446744073709551615") => self::Bytes8,
                default => null,
            };
        }

        return null;
    }

    /**
     * Checks if the first number is less than or equal to the second number.
     */
    private static function leqDec(string $a, string $b): bool
    {
        $a = ltrim($a, "0");
        $b = ltrim($b, "0");
        if ($a === "") $a = "0";
        if ($b === "") $b = "0";
        $la = strlen($a);
        $lb = strlen($b);
        return $la < $lb || ($la === $lb && $a <= $b);
    }

    /**
     * Checks if the given number fits within a defined size constraint.
     */
    public function fits(int|string $n): false|int|string
    {
        return ($s = self::getSize($n)) && $this->value >= $s->value ? $n : false;
    }
}