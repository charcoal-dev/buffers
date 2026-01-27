<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Codecs\TLV;

use Charcoal\Buffers\Contracts\Codecs\TLV\ProtocolEnumInterface;

/**
 * Represents an envelope containing protocol and associated frames.
 */
final readonly class Envelope
{
    /** @var Frame[] */
    public array $frames;

    public function __construct(
        public ProtocolEnumInterface $protocol,
        Frame                        ...$frames
    )
    {
        $this->frames = $frames;
    }
}