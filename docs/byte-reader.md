# Byte Reader

The `ByteReader` provides a way to read data sequentially from a buffer using an internal pointer. It is ideal for parsing binary protocols or file formats where data is structured in a specific sequence.

## Creating a Reader

You can create a reader directly from any buffer or by calling the `read()` method on a buffer instance:

```php
use Charcoal\Buffers\Buffer;

$buffer = new Buffer("\x01\x00\x02\x00\x00\x00\x03");
$reader = $buffer->read();
```

## Sequential Reading

The reader maintains a pointer that advances automatically as you read data:

```php
// Read a single byte
$version = $reader->readUInt8(); // 1

// Read a 16-bit integer (Little Endian)
$type = $reader->readUInt16LE(); // 2

// Read a 32-bit integer (Little Endian)
$length = $reader->readUInt32LE(); // 3
```

### Supported Integer Methods

*   `readUInt8()`: Reads 1 byte.
*   `readUInt16LE()` / `readUInt16BE()`: Reads 2 bytes.
*   `readUInt32LE()` / `readUInt32BE()`: Reads 4 bytes.
*   `readUInt64()`: Reads 8 bytes (returns as string for compatibility).

## Navigation and Inspection

You can manually control the reading pointer or look at data without moving it:

*   **`pos()`**: Get the current pointer position.
*   **`setPointer(int $pos)`**: Jump to a specific position.
*   **`reset()`**: Return to the beginning.
*   **`remaining()`**: Check how many bytes are left to read.
*   **`isEnd()`**: Returns `true` if all bytes have been read.

### Look-ahead and Look-behind

Sometimes you need to peek at the data without advancing the pointer:

```php
// Peek at the next 2 bytes
$peek = $reader->lookAhead(2);

// See what was just read (2 bytes back)
$history = $reader->lookBehind(2);

// Get everything from the current position to the end
$rest = $reader->getRest();
```

## Safety

The `ByteReader` ensures you don't read beyond the buffer's boundaries. If you attempt to read more bytes than available, an `UnderflowException` will be thrown, preventing invalid data access.
