<?php
/*
 * This file is a part of "charcoal-dev/buffers" package.
 * https://github.com/charcoal-dev/buffers
 *
 * Copyright (c) Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/charcoal-dev/buffers/blob/master/LICENSE
 */

declare(strict_types=1);

/**
 * Class CodecsTest
 */
class CodecsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     */
    public function testBase16(): void
    {
        $raw = hash("sha256", "furqansiddiqui", true);
        $hex = bin2hex($raw);

        $frame1 = \Charcoal\Buffers\Frames\Bytes32::fromBase16("0x" . $hex);
        $frame2 = \Charcoal\Buffers\Frames\Bytes32::fromBase16($hex);

        $this->assertTrue($frame1->equals($frame2), "Testing that 0x prefix is discarded");
        $this->assertEquals($frame1->toBase16(), $hex, "Compare hex strings");
        $this->assertEquals($frame1->raw(), $raw, "Compare raw strings");
    }
}