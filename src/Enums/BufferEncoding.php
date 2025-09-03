<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Enums;

use Charcoal\Contracts\Buffers\ReadableBufferInterface;
use Charcoal\Contracts\Encoding\EncodingSchemeInterface;

/**
 * Represents several encoding schemes to encode and decode data, including
 * Base16, Base64, and URL-safe Base64.
 */
enum BufferEncoding implements EncodingSchemeInterface
{
    case Base16;
    case Base64;
    case Base64Url;

    /**
     * @param ReadableBufferInterface|string $raw
     * @return string
     */
    public function encode(ReadableBufferInterface|string $raw): string
    {
        if ($raw instanceof ReadableBufferInterface) {
            $raw = $raw->bytes();
        }

        return match ($this) {
            self::Base16 => self::encodeBase16($raw),
            self::Base64,
            self::Base64Url => self::encodeBase64($raw, $this === self::Base64Url),
        };
    }

    /**
     * @param string $encoded
     * @return string
     */
    public function decode(string $encoded): string
    {
        return match ($this) {
            self::Base16 => self::decodeBase16($encoded),
            self::Base64,
            self::Base64Url => self::encodeBase64($encoded, $this === self::Base64Url),
        };
    }

    /**
     * @param string $encoded
     * @return string
     */
    public static function decodeBase16(string $encoded): string
    {
        if (str_starts_with($encoded, "0x")) {
            $encoded = substr($encoded, 2);
        }

        if (!ctype_xdigit($encoded)) {
            throw new \InvalidArgumentException("Invalid hex string");
        }

        if (strlen($encoded) % 2 !== 0) {
            $encoded = "0" . $encoded;
        }

        return hex2bin($encoded);
    }

    /**
     * Encodes a given string into a Base16 encoded string.
     */
    public static function encodeBase16(string $raw): string
    {
        $b16 = bin2hex($raw);
        if (strlen($b16) % 2 !== 0) {
            $b16 = "0" . $b16;
        }

        return $b16;
    }

    /**
     * Encodes a given string into a Base64 encoded string.
     * Optionally, the encoding can be made URL-safe.
     */
    public static function encodeBase64(string $raw, bool $urlSafe = false): string
    {
        $b64 = base64_encode($raw);
        return $urlSafe ? rtrim(strtr($b64, "+/", "-_"), "=") : $b64;
    }

    /**
     * Decodes a Base64 encoded string,
     * ensuring correct padding and substitution of URL-safe characters.
     */
    public static function decodeBase64(string $encoded): string
    {
        if (!str_ends_with($encoded, "=") || (strpos($encoded, "-") > 0 || strpos($encoded, "_") > 0)) {
            $encoded = strtr($encoded, "-_", "+/");
            $encoded .= str_repeat("=", (4 - strlen($encoded) % 4) % 4);
        }

        return base64_decode($encoded, true) ?: "";
    }
}