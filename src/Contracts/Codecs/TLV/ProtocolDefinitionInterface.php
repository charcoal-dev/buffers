<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Contracts\Codecs\TLV;

/**
 * Interface for defining methods to retrieve protocol-related enumerations.
 */
interface ProtocolDefinitionInterface
{
    /**
     * @param int $protocolCode
     * @return ProtocolEnumInterface
     */
    public function getProtocol(int $protocolCode): ProtocolEnumInterface;

    /**
     * @param int $frameCode
     * @return FrameCodeEnumInterface
     */
    public function getFrameCode(int $frameCode): FrameCodeEnumInterface;

    /**
     * @param int $paramTypeCode
     * @return ParamTypeEnumInterface
     */
    public function getParamType(int $paramTypeCode): ParamTypeEnumInterface;
}