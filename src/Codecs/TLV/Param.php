<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers\Codecs\TLV;

use Charcoal\Buffers\Contracts\Codecs\TLV\ParamTypeEnumInterface;

/**
 * Represents a parameter with a defined type and value.
 */
final readonly class Param
{
    /**
     * @param ParamTypeEnumInterface $type
     * @param mixed $value
     */
    public function __construct(
        public ParamTypeEnumInterface $type,
        public mixed                  $value
    )
    {
    }
}