<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Codecs\TLV;

use Charcoal\Buffers\Contracts\Codecs\TLV\ProtocolDefinitionInterface;
use Charcoal\Buffers\Support\ByteReader;
use Charcoal\Contracts\Buffers\ReadableBufferInterface;

/**
 * Decodes a byte stream into an Envelope object based on the provided protocol definition.
 */
final class TlvBinaryCodec
{
    /**
     * @param ProtocolDefinitionInterface $protocol
     * @param string|ReadableBufferInterface $bytes
     * @return Envelope
     */
    public static function decode(
        ProtocolDefinitionInterface    $protocol,
        string|ReadableBufferInterface $bytes
    ): Envelope
    {
        $bytes = new ByteReader($bytes);
        $protocolEnum = $protocol->getProtocol($bytes->readUInt8());
        $byteOrder = $protocolEnum->getByteOrder();
        $framesCount = $bytes->readUInt8();
        if ($framesCount < 1 || $framesCount > $protocolEnum->getFramesCap()) {
            throw new \InvalidArgumentException("Invalid frames count: " . $framesCount);
        }

        $frames = [];
        for ($i = 0; $i < $framesCount; $i++) {
            try {
                $frameCode = $protocol->getFrameCode($bytes->readUInt8());
                $paramsCount = $bytes->readUInt8();
                if ($paramsCount > $protocolEnum->getParamsCap()) {
                    throw new \InvalidArgumentException("Invalid params count: " . $paramsCount);
                }

                $params = [];
                for ($pN = 0; $pN < $paramsCount; $pN++) {
                    $paramType = $protocol->getParamType($bytes->readUInt8());
                    $lenBits = $paramType->getLengthBits();
                    $paramLength = $byteOrder->unpack32($bytes->next($lenBits->getByteSize()), $lenBits);
                    if ($paramLength > $protocolEnum->getMaxLength()) {
                        throw new \OverflowException("Param length exceeds maximum allowed: " . $paramLength);
                    }

                    $paramsBytes = $paramLength > 0 ? $bytes->next($paramLength) : null;
                    $params[] = new Param($paramType, $paramType->decode($paramsBytes, $protocolEnum));
                }

                unset($paramType, $paramLength, $paramsBytes);
                $frames[] = new Frame($frameCode, ...$params);
            } catch (\Throwable $t) {
                throw new \RuntimeException(sprintf("[%s][%s]%s Decoding Exception: [%s] %s",
                    $protocolEnum->name,
                    isset($frameCode) ? $frameCode->name : "Frame#" . $i,
                    isset($pN) ? "[Param#" . $pN . "]" : "",
                    $t::class,
                    $t->getMessage()),
                    previous: $t);
            }
        }

        if ($bytes->remaining()) {
            throw new \UnexpectedValueException(sprintf("[%s] Unexpected bytes remaining: %d",
                $protocolEnum->name, $bytes->remaining()));
        }

        return new Envelope($protocolEnum, ...$frames);
    }

    /**
     * @param Envelope $envelope
     * @return string
     */
    public static function encode(Envelope $envelope): string
    {
        $protocolEnum = $envelope->protocol;
        $byteOrder = $protocolEnum->getByteOrder();
        $framesCount = count($envelope->frames);
        if ($framesCount < 1 || $framesCount > $protocolEnum->getFramesCap() || $framesCount > 255) {
            throw new \InvalidArgumentException("Invalid frames count: " . $framesCount);
        }

        $protocolId = $protocolEnum->getId();
        if ($protocolId > 255) {
            throw new \InvalidArgumentException("Invalid protocol ID: " . $protocolEnum->getId());
        }

        $encoded = chr($protocolId);
        $encoded .= chr($framesCount);
        foreach ($envelope->frames as $frame) {
            $paramsCount = count($frame->params);
            if ($paramsCount > $protocolEnum->getParamsCap() || $paramsCount > 255) {
                throw new \InvalidArgumentException("Invalid params count: " . $paramsCount);
            }

            $frameCode = $frame->frameCode->getId();
            if ($frameCode > 255) {
                throw new \InvalidArgumentException("Invalid frame code: " . $frameCode);
            }

            $encoded .= chr($frameCode);
            $encoded .= chr($paramsCount);
            for ($pN = 0; $pN < $paramsCount; $pN++) {
                try {
                    $param = $frame->params[$pN];
                    $paramEncoded = $param->type->encode($param->value, $protocolEnum);
                    $paramLen = strlen($paramEncoded);
                    if ($paramLen > $protocolEnum->getMaxLength()) {
                        throw new \OverflowException("Param length exceeds maximum allowed: " . $paramLen);
                    }

                    $lenBits = $param->type->getLengthBits();
                    $paramTypeId = $param->type->getId();
                    if ($paramTypeId > 255) {
                        throw new \InvalidArgumentException("Invalid param type ID: " . $paramTypeId);
                    }

                    $encoded .= chr($paramTypeId);
                    $encoded .= $byteOrder->pack32($lenBits, $paramLen);
                    $encoded .= $paramEncoded;
                    unset($param, $paramTypeId, $paramEncoded, $paramLen, $lenBits);
                } catch (\Throwable $t) {
                    throw new \RuntimeException(sprintf(
                        "[%s][%s][Param#%d] Encoding Exception: [%s] %s",
                        $protocolEnum->name,
                        $frame->frameCode->name,
                        $pN,
                        $t::class,
                        $t->getMessage()
                    ), previous: $t);
                }
            }
        }

        return $encoded;
    }
}