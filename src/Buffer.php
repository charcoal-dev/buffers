<?php
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers;

/**
 * Class Buffer
 * @package Charcoal\Buffers
 */
class Buffer extends AbstractWritableBuffer
{
    /**
     * @param string|null $data
     */
    public function __construct(?string $data = null)
    {
        parent::__construct($data);
        $this->readOnly = false;
    }
}

