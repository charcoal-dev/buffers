<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers;

use Charcoal\Adapters\GMP\BigInteger;

/**
 * Class ByteOrder
 * @package Charcoal\Buffers
 */
class ByteOrder
{
    public const int LITTLE_ENDIAN = 0x01;
    public const int BIG_ENDIAN = 0x02;

    /** @var bool|null */
    protected static ?bool $machineIsLittleEndian = null;
    /** @var bool|null */
    protected static ?bool $gmpIsLittleEndian = null;

    /**
     * @param string $inp
     * @param bool $checkHex
     * @return string
     */
    public static function SwapEndianness(string $inp, bool $checkHex = true): string
    {
        $isHex = $checkHex && preg_match('/^[a-f0-9]+$/i', $inp);
        return implode("", array_reverse(str_split($inp, $isHex ? 2 : 1)));
    }

    /**
     * @return int
     */
    public static function gmpEndianness(): int
    {
        if (!is_bool(static::$gmpIsLittleEndian)) {
            static::$gmpIsLittleEndian = gmp_strval(gmp_init(65534, 10), 16) === "feff";
        }

        return static::$gmpIsLittleEndian ? self::LITTLE_ENDIAN : self::BIG_ENDIAN;
    }

    /**
     * @return bool
     */
    public static function isLittleEndian(): bool
    {
        if (!is_bool(static::$machineIsLittleEndian)) {
            static::$machineIsLittleEndian = pack("S", 1) === pack("v", 1);
        }

        return static::$machineIsLittleEndian;
    }

    /**
     * @return bool
     */
    public static function isBigEndian(): bool
    {
        return !static::isLittleEndian();
    }


    /**
     * Checks if provided integer of up to 32-bits can be packed into given N byteLen.
     * @param int $n
     * @param int $byteLen
     * @return void
     */
    public static function CheckUInt32(int $n, int $byteLen): void
    {
        $max = match ($byteLen) {
            1 => 0xff,
            2 => 0xffff,
            4 => 0xffffffff,
            default => throw new \OutOfBoundsException("Invalid integer byte len")
        };

        if ($n < 0) {
            throw new \UnderflowException("Expected unsigned integer; got a signed/negative value");
        }

        if ($n > $max) {
            throw new \OverflowException("Argument integer cannot be packed in $byteLen bytes");
        }
    }

    /**
     * Checks in given integer can be packed in 8 bytes (64-bit).
     * @param int|string|\GMP|\Charcoal\Buffers\AbstractByteArray|\Charcoal\Adapters\GMP\BigInteger $n
     * @return \Charcoal\Adapters\GMP\BigInteger
     */
    public static function CheckUInt64(int|string|\GMP|AbstractByteArray|BigInteger $n): BigInteger
    {
        if (!$n instanceof BigInteger) {
            $n = new BigInteger($n);
        }

        if ($n->isSigned()) {
            throw new \UnderflowException("Expected unsigned integer; got a signed/negative value");
        }

        if ($n->greaterThan("18446744073709551615")) {
            throw new \OverflowException("Value cannot exceed 18446744073709551615 to be packed in 8 bytes");
        }

        return $n;
    }
}