<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Tests;

use Charcoal\Buffers\Buffer;
use Charcoal\Buffers\Frames\Bytes16;
use Charcoal\Buffers\Frames\Bytes20;
use Charcoal\Buffers\Frames\Bytes32;

/**
 * Class ByteDigestTest
 */
class ByteDigestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Verify basic hashes
     * @return void
     */
    public function testHashes(): void
    {
        $buffer = new Buffer("charcoal.dev");

        $b1md5 = hash("md5", "charcoal.dev", true);
        $b1sha1 = hash("sha1", "charcoal.dev", false);
        $b1sha256 = hash("sha256", "charcoal.dev", true);

        $this->assertEquals($b1md5, $buffer->hash()->md5()->raw());
        $this->assertEquals($b1sha1, $buffer->hash()->sha1()->toBase16());
        $this->assertEquals($b1sha256, $buffer->hash()->sha256()->raw());
    }

    /**
     * Getting result as string with "returnString" arg while "toString" method set
     * @return void
     */
    public function testResultAsString(): void
    {
        $digest = (new Buffer("charcoal.dev"))->hash();
        $digest->toString(); // All result should be string

        $this->assertIsString($digest->hash("sha1"));
        $this->assertIsObject($digest->hash("sha1", returnString: false)); // Passing returnString arg overrides toString method
    }

    /**
     * Getting result as string with "returnString" arg while "toString" method NOT set
     * @return void
     */
    public function testResultAsString2(): void
    {
        $digest = (new Buffer("charcoal.dev"))->hash(); // All result should be objects

        $this->assertIsNotString($digest->hash("sha1"));
        $this->assertIsString($digest->hash("sha1", returnString: true)); // Passing returnString arg overrides toString method
        $this->assertIsObject($digest->hash("sha1"));
    }

    /**
     * Verify return types of various methods
     * - Methods "hash", "pbkdf2", "hmac" must return instance of Buffer (set as readOnly)
     * - Methods "md5", "sha1", "sha256", "ripeMd160" must return fixed length frames buffer
     * - Method "sha512" will return Buffer (set as readOnly)
     * @return void
     */
    public function testReturnObjects(): void
    {
        $digest = (new Buffer("charcoal.dev"))->hash();
        $this->assertInstanceOf(Buffer::class, $digest->hash("sha1"));
        $this->assertInstanceOf(Bytes20::class, $digest->sha1());
        $this->assertInstanceOf(Bytes32::class, $digest->sha256());
        $this->assertInstanceOf(Bytes20::class, $digest->ripeMd160());
        $this->assertInstanceOf(Bytes16::class, $digest->md5());

        $this->assertInstanceOf(Buffer::class, $digest->hmac("sha256", new Buffer('some-key')));
        $this->assertInstanceOf(Buffer::class, $digest->pbkdf2("sha256", "random-salt", 100));
    }
}