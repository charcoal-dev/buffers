<?php /**  */
/**
 * Part of the "charcoal-dev/buffers" package.
 * @link https://github.com/charcoal-dev/buffers
 */

declare(strict_types=1);

namespace Charcoal\Buffers;

use Charcoal\Buffers\Traits\BufferDecodeTrait;
use Charcoal\Buffers\Traits\ReadableBufferTrait;
use Charcoal\Contracts\Buffers\Immutable\BufferSpinOffInterface;
use Charcoal\Contracts\Buffers\Immutable\ImmutableBufferInterface;
use Charcoal\Contracts\Buffers\ReadableBufferInterface;

/**
 * Represents an immutable buffer of bytes. This class provides methods
 * for creating modified copies of the buffer with data appended or prepended,
 * without altering the original instance. It also supports accessing substrings
 * of the buffer's data.
 */
final readonly class BufferImmutable implements
    ImmutableBufferInterface,
    ReadableBufferInterface,
    BufferSpinOffInterface
{
    use ReadableBufferTrait;
    use BufferDecodeTrait;

    private int $length;

    public function __construct(private string $bytes)
    {
        $this->length = strlen($this->bytes);
    }

    /**
     * @return array
     */
    public function __serialize(): array
    {
        return [
            "bytes" => $this->bytes,
        ];
    }

    /**
     * @param array $data
     * @return void
     */
    public function __unserialize(array $data): void
    {
        $this->bytes = $data["bytes"];
        $this->length = strlen($this->bytes);
    }

    /**
     * Returns a new instance with data appended.
     * @param ReadableBufferInterface|string $data
     * @return BufferImmutable
     */
    public function withAppended(ReadableBufferInterface|string $data): self
    {
        return new BufferImmutable($this->bytes .
            ($data instanceof ReadableBufferInterface ? $data->bytes() : $data));
    }

    /**
     * Returns a new instance with data prepended.
     * @param ReadableBufferInterface|string $data
     * @return BufferImmutable
     */
    public function withPrepended(ReadableBufferInterface|string $data): self
    {
        return new BufferImmutable(($data instanceof ReadableBufferInterface ? $data->bytes() : $data)
            . $this->bytes);
    }
}