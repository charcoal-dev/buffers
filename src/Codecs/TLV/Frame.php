<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Codecs\TLV;

use Charcoal\Buffers\Contracts\Codecs\TLV\FrameCodeEnumInterface;

/**
 * Represents a read-only Frame entity containing protocol, frame code, and associated parameters.
 */
final readonly class Frame
{
    /** @var Param[] */
    public array $params;

    /**
     * @param FrameCodeEnumInterface $frameCode
     * @param Param ...$params
     */
    public function __construct(
        public FrameCodeEnumInterface $frameCode,
        Param                         ...$params
    )
    {
        $this->params = $params;
    }
}