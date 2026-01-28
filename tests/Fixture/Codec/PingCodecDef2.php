<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Tests\Fixture\Codec;

use Charcoal\Buffers\Codecs\TLV\Types\TlvParamType;
use Charcoal\Buffers\Contracts\Codecs\TLV\ProtocolDefinitionInterface;

class PingCodecDef2 implements ProtocolDefinitionInterface
{
    public function getProtocol(int $protocolCode): PingProtocol2
    {
        return PingProtocol2::from($protocolCode);
    }

    public function getFrameCode(int $frameCode): PingFrame
    {
        return PingFrame::from($frameCode);
    }

    public function getParamType(int $paramTypeCode): TlvParamType
    {
        return TlvParamType::from($paramTypeCode);
    }
}