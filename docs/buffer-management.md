# Buffer Management

The library provides two primary ways to handle byte buffers: **Mutable Buffers** for active data manipulation and *
*Immutable Buffers** for safe, read-only data sharing.

## Mutable Buffers (`Buffer`)

The `Buffer` class allows you to create a workspace for building and modifying byte sequences. This is particularly
useful when constructing network packets, file formats, or any binary data where you need to append information
dynamically.

### Basic Usage

You can initialize a buffer with a string or another buffer:

```php
use Charcoal\Buffers\Buffer;

$buffer = new Buffer("Initial data");
$buffer->append(" - more data");
echo $buffer->bytes(); // "Initial data - more data"
```

### Writing Integers

The buffer supports writing unsigned integers directly, handling the byte packing for you:

```php
use Charcoal\Buffers\Enums\ByteOrder;

$buffer = new Buffer();
$buffer->writeUInt8(0x01);
$buffer->writeUInt16(0x1234, ByteOrder::BigEndian);
$buffer->writeUInt32(0x567890AB, ByteOrder::LittleEndian);
```

### Control and Safety

* **Locking**: You can lock a buffer to prevent any further modifications.
* **Max Size**: You can set a maximum size to prevent memory exhaustion or ensure protocol compliance.
* **Flushing**: Quickly clear the buffer content using `flush()`.

```php
$buffer->setMaxSize(1024);
$buffer->lock();
// $buffer->append("this will throw a DomainException");
```

---

## Immutable Buffers (`BufferImmutable`)

`BufferImmutable` is designed for cases where you want to ensure the data remains constant. If you perform a 
"modification" on an immutable buffer, it returns a **new instance** instead of changing the original one.

```php
use Charcoal\Buffers\BufferImmutable;

$original = new BufferImmutable("static data");
$new = $original->withAppended(" - extension");

echo $original->bytes(); // "static data"
echo $new->bytes();      // "static data - extension"
```

---

## Common Features

Both buffer types share a set of powerful utilities for searching and inspecting data:

* **Search**: Check if a buffer `contains()`, `startsWith()`, or `endsWith()` a specific byte sequence.
* **Slicing**: Use `subString()` to extract parts of the buffer without modifying it.
* **Comparison**: Securely compare buffers using `equals()`, which uses `hash_equals` to prevent timing attacks.
* **Encoding**: Encode your binary data to Hex, Base64, or other formats using the `encode()` method with a compatible
  scheme.
