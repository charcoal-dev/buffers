<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Tests;

use Charcoal\Buffers\Codecs\TLV\Envelope;
use Charcoal\Buffers\Codecs\TLV\Frame;
use Charcoal\Buffers\Codecs\TLV\Param;
use Charcoal\Buffers\Codecs\TLV\TlvBinaryCodec;
use Charcoal\Buffers\Codecs\TLV\Types\TlvParamType;
use Charcoal\Buffers\Tests\Fixture\Codec\PingCodecTest;
use Charcoal\Buffers\Tests\Fixture\Codec\PingFrame;
use Charcoal\Buffers\Tests\Fixture\Codec\PingProtocol;
use PHPUnit\Framework\TestCase;

/**
 * Class TlvCodecTest
 * @package Charcoal\Buffers\Tests
 */
class TlvCodecTest extends TestCase
{
    /**
     * @return void
     */
    public function testPingProtocol(): void
    {
        $protocol = new PingCodecTest();

        // Create a Ping frame with a timestamp and a message
        $timestamp = time();
        $message = "Hello TLV";

        $frame = new Frame(
            PingFrame::Ping,
            new Param(TlvParamType::UInt32, $timestamp),
            new Param(TlvParamType::String, $message)
        );

        $envelope = new Envelope(PingProtocol::V1, $frame);

        $encoded = TlvBinaryCodec::encode($envelope);
        $this->assertNotEmpty($encoded);
        $this->assertSame(PingProtocol::V1->getId(), ord($encoded[0]));
        $this->assertSame(1, ord($encoded[1]));
        $this->assertSame(PingFrame::Ping->getId(), ord($encoded[2]));
        $this->assertSame(2, ord($encoded[3]));

        $decoded = TlvBinaryCodec::decode($protocol, $encoded);
        $this->assertSame(PingProtocol::V1, $decoded->protocol);
        $this->assertCount(1, $decoded->frames);

        $decodedFrame = $decoded->frames[0];
        $this->assertSame(PingFrame::Ping, $decodedFrame->frameCode);
        $this->assertCount(2, $decodedFrame->params);

        $this->assertSame($timestamp, $decodedFrame->params[0]->value);
        $this->assertSame($message, $decodedFrame->params[1]->value);
    }

    /**
     * @return void
     */
    public function testVariousDataTypes(): void
    {
        $protocol = new PingCodecTest();

        $frame = new Frame(
            PingFrame::Ping,
            new Param(TlvParamType::UInt8, 255),
            new Param(TlvParamType::UInt16, 65535),
            new Param(TlvParamType::UInt32, 4294967295),
            new Param(TlvParamType::UInt64, 9223372036854775807), // Max signed 64-bit int
            new Param(TlvParamType::Bool, true),
            new Param(TlvParamType::Bool, false),
            new Param(TlvParamType::Null, null)
        );

        $envelope = new Envelope(PingProtocol::V1, $frame);
        $encoded = TlvBinaryCodec::encode($envelope);
        $decoded = TlvBinaryCodec::decode($protocol, $encoded);

        $decodedFrame = $decoded->frames[0];
        $this->assertCount(7, $decodedFrame->params);

        $this->assertSame(255, $decodedFrame->params[0]->value);
        $this->assertSame(65535, $decodedFrame->params[1]->value);
        $this->assertSame(4294967295, $decodedFrame->params[2]->value);
        $this->assertSame(9223372036854775807, $decodedFrame->params[3]->value);
        $this->assertSame(true, $decodedFrame->params[4]->value);
        $this->assertSame(false, $decodedFrame->params[5]->value);
        $this->assertNull($decodedFrame->params[6]->value);
    }

    /**
     * @return void
     */
    public function testLongString(): void
    {
        $protocol = new PingCodecTest();

        // TlvParamType::String uses UInt::Bytes2 for length, so up to 65535 bytes
        $longString = str_repeat("A", 500);

        $frame = new Frame(
            PingFrame::Ping,
            new Param(TlvParamType::String, $longString)
        );

        $envelope = new Envelope(PingProtocol::V1, $frame);
        $encoded = TlvBinaryCodec::encode($envelope);
        $decoded = TlvBinaryCodec::decode($protocol, $encoded);

        $this->assertSame($longString, $decoded->frames[0]->params[0]->value);
        $this->assertEquals(500, strlen($decoded->frames[0]->params[0]->value));
    }

    /**
     * @return void
     */
    public function testNullAndBoolWireLayout(): void
    {
        $frame = new Frame(
            PingFrame::Ping,
            new Param(TlvParamType::Null, null),
            new Param(TlvParamType::Bool, false),
            new Param(TlvParamType::Bool, true),
        );

        $envelope = new Envelope(PingProtocol::V1, $frame);
        $bin = TlvBinaryCodec::encode($envelope);
        $hex = bin2hex($bin);

        /**
         * Expected wire layout:
         *
         * Envelope:
         *   protocolId   = 01
         *   framesCount  = 01
         *
         * Frame:
         *   frameCode    = 01
         *   paramsCount  = 03
         *
         * Param #1 Null:
         *   typeId       = 00
         *   length       = 00
         *   value        = (empty)
         *
         * Param #2 Bool false:
         *   typeId       = 05
         *   length       = 01
         *   value        = 00
         *
         * Param #3 Bool true:
         *   typeId       = 05
         *   length       = 01
         *   value        = 01
         */

        $expectedHex =
            "01" . // protocolId
            "01" . // framesCount
            "01" . // frameCode
            "03" . // paramsCount
            "00" . // Null type
            "00" . // length=0
            "05" . // Bool type
            "01" . // length=1
            "00" . // false payload
            "05" . // Bool type
            "01" . // length=1
            "01";  // true payload

        $this->assertSame($expectedHex, $hex);
    }

    /**
     * @return void
     */
    public function testStringLengthIsTwoBytes(): void
    {
        $frame = new Frame(
            PingFrame::Ping,
            new Param(TlvParamType::String, "Hi")
        );

        $env = new Envelope(PingProtocol::V1, $frame);
        $hex = bin2hex(TlvBinaryCodec::encode($env));

        // Param layout:
        // type = 06
        // length = 0002 (UInt16)
        // value = 4869 ("Hi")
        $expected = "01010101" . "06" . "0002" . "4869";
        $this->assertSame($expected, $hex);
    }

    /**
     * @return void
     */
    public function testNullAlwaysHasZeroLength(): void
    {
        $frame = new Frame(
            PingFrame::Ping,
            new Param(TlvParamType::Null, null)
        );

        $env = new Envelope(PingProtocol::V1, $frame);
        $hex = bin2hex(TlvBinaryCodec::encode($env));

        $expected = "01010101" . "00" . "00";
        $this->assertSame($expected, $hex);
    }

    /**
     * @return void
     */
    public function testBoolAlwaysHasLengthOne(): void
    {
        $frame = new Frame(
            PingFrame::Ping,
            new Param(TlvParamType::Bool, true)
        );

        $env = new Envelope(PingProtocol::V1, $frame);
        $hex = bin2hex(TlvBinaryCodec::encode($env));

        $expected = "01010101" . "05" . "01" . "01";
        $this->assertSame($expected, $hex);
    }
    /**
     * @return void
     */
    public function testIntWireLayout(): void
    {
        $frame = new Frame(
            PingFrame::Ping,
            new Param(TlvParamType::UInt8, 0x12),
            new Param(TlvParamType::UInt16, 0x1234),
            new Param(TlvParamType::UInt32, 0x12345678),
            // UInt64 0x1122334455667788
            new Param(TlvParamType::UInt64, "1234605616436508552")
        );

        $envelope = new Envelope(PingProtocol::V1, $frame);
        $hex = bin2hex(TlvBinaryCodec::encode($envelope));

        /**
         * Expected:
         * 01 (Proto)
         * 01 (Frames)
         * 01 (FrameCode)
         * 04 (ParamsCount)
         *
         * Param 1 (UInt8):
         * 01 (Type)
         * 01 (Len)
         * 12 (Val)
         *
         * Param 2 (UInt16):
         * 02 (Type)
         * 02 (Len)
         * 1234 (Val)
         *
         * Param 3 (UInt32):
         * 03 (Type)
         * 04 (Len)
         * 12345678 (Val)
         *
         * Param 4 (UInt64):
         * 04 (Type)
         * 08 (Len)
         * 1122334455667788 (Val)
         */

        $expected = "01010104" .
            "010112" .
            "02021234" .
            "030412345678" .
            "04081122334455667788";

        $this->assertSame($expected, $hex);
    }

    /**
     * @return void
     */
    public function testLongStringWireLayout(): void
    {
        $longString = str_repeat("B", 300); // 300 = 0x012C
        $frame = new Frame(
            PingFrame::Ping,
            new Param(TlvParamType::String, $longString)
        );

        $envelope = new Envelope(PingProtocol::V1, $frame);
        $hex = bin2hex(TlvBinaryCodec::encode($envelope));

        // Proto(1) + FramesCount(1) + FrameCode(1) + ParamsCount(1)
        // Type(1) + Len(2) + Val(300)
        $header = "01010101";
        $paramHeader = "06" . "012c";
        $expectedHex = $header . $paramHeader . bin2hex($longString);

        $this->assertSame($expectedHex, $hex);
    }

    /**
     * @return void
     */
    public function testMultiFrameWireLayout(): void
    {
        $frame1 = new Frame(
            PingFrame::Ping,
            new Param(TlvParamType::UInt8, 1)
        );
        $frame2 = new Frame(
            PingFrame::Ping,
            new Param(TlvParamType::UInt8, 2)
        );

        $envelope = new Envelope(PingProtocol::V1, $frame1, $frame2);
        $hex = bin2hex(TlvBinaryCodec::encode($envelope));

        /**
         * 01 (Proto)
         * 02 (FramesCount)
         *
         * Frame 1:
         * 01 (FrameCode)
         * 01 (ParamsCount)
         * 01 (Type UInt8)
         * 01 (Len)
         * 01 (Val)
         *
         * Frame 2:
         * 01 (FrameCode)
         * 01 (ParamsCount)
         * 01 (Type UInt8)
         * 01 (Len)
         * 02 (Val)
         */

        $expected = "0102" . "0101010101" . "0101010102";
        $this->assertSame($expected, $hex);
    }
}