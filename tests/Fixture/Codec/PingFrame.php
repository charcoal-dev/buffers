<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Tests\Fixture\Codec;

use Charcoal\Buffers\Contracts\Codecs\TLV\FrameCodeEnumInterface;

enum PingFrame: int implements FrameCodeEnumInterface
{
    case Ping = 1;
    case Pong = 2;

    public function getId(): int
    {
        return $this->value;
    }
}