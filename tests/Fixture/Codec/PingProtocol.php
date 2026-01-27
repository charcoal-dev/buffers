<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Tests\Fixture\Codec;

use Charcoal\Buffers\Contracts\Codecs\TLV\ProtocolEnumInterface;
use Charcoal\Buffers\Enums\ByteOrder;
use Charcoal\Buffers\Enums\UInt;

enum PingProtocol: int implements ProtocolEnumInterface
{
    case V1 = 1;

    public function getId(): int
    {
        return $this->value;
    }

    public function getByteOrder(): ByteOrder
    {
        return ByteOrder::BigEndian;
    }

    public function getLengthBits(): UInt
    {
        return UInt::Byte;
    }

    public function getFramesCap(): int
    {
        return 10;
    }

    public function getParamsCap(): int
    {
        return 10;
    }

    public function getMaxLength(): int
    {
        return 1024;
    }
}