# Endianness and Byte Order

When working with binary data, the order of bytes (Endianness) is crucial. This library provides tools to handle
different byte orders and detect the host system's architecture.

## Byte Order (`ByteOrder`)

The `ByteOrder` enum simplifies the process of packing and unpacking integers of various sizes (8, 16, or 32 bits).

### Packing Integers

Packing converts an integer into a raw byte string based on the chosen endianness:

```php
use Charcoal\Buffers\Enums\ByteOrder;
use Charcoal\Buffers\Enums\UInt;

$order = ByteOrder::BigEndian;
$bytes = $order->pack32(UInt::Bytes2, 0x1234); 
// Result: "\x12\x34"

$order = ByteOrder::LittleEndian;
$bytes = $order->pack32(UInt::Bytes2, 0x1234); 
// Result: "\x34\x12"
```

### Unpacking Integers

Unpacking converts a raw byte string back into a PHP integer:

```php
$val = $order->unpack32("\x34\x12", UInt::Bytes2);
// Result: 4660 (0x1234)
```

## System Endianness (`Endianness`)

The `Endianness` utility class helps you identify the endianness of the machine running the code, which is useful for
architecture-dependent binary formats.

```php
use Charcoal\Buffers\Support\Endianness;

if (Endianness::isLittleEndian()) {
    // The current machine is Little Endian (common for x86/x64)
}

if (Endianness::isBigEndian()) {
    // The current machine is Big Endian
}
```

### Manual Swapping

If you need to manually reverse the byte order of a string (e.g., to convert between LE and BE), you can use the
`swap()` method:

```php
$reversed = Endianness::swap("\x01\x02\x03\x04");
// Result: "\x04\x03\x02\x01"
```

## Unsigned Integer Constraints (`UInt`)

The `UInt` enum is used throughout the library to define the size of unsigned integers:

* `UInt::Byte`: 8-bit (one byte)
* `UInt::Bytes2`: 16-bit (2 bytes)
* `UInt::Bytes4`: 32-bit (4 bytes)
* `UInt::Bytes8`: 64-bit (8 bytes)

It also provides a `fits()` method to validate if a number can be represented within a certain number of bits without
overflow.
