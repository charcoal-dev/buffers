<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Tests\Fixture\Codec;

use Charcoal\Buffers\Codecs\TLV\Types\TlvParamType;
use Charcoal\Buffers\Contracts\Codecs\TLV\FrameCodeEnumInterface;
use Charcoal\Buffers\Contracts\Codecs\TLV\ParamTypeEnumInterface;
use Charcoal\Buffers\Contracts\Codecs\TLV\ProtocolDefinitionInterface;
use Charcoal\Buffers\Contracts\Codecs\TLV\ProtocolEnumInterface;

class PingCodecTest implements ProtocolDefinitionInterface
{
    public function getProtocol(int $protocolCode): ProtocolEnumInterface
    {
        return PingProtocol::from($protocolCode);
    }

    public function getFrameCode(int $frameCode): FrameCodeEnumInterface
    {
        return PingFrame::from($frameCode);
    }

    public function getParamType(int $paramTypeCode): ParamTypeEnumInterface
    {
        return TlvParamType::from($paramTypeCode);
    }
}