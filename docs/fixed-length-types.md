# Fixed-Length Types

For scenarios where you need buffers of a specific, unchanging size (like cryptographic hashes, IDs, or fixed-size
protocol headers), the library provides specialized fixed-length buffer classes.

## Available Types

The library includes several pre-defined fixed-length immutable buffers:

* `Bytes16`: 16 bytes (128 bits)
* `Bytes20`: 20 bytes (160 bits)
* `Bytes24`: 24 bytes (192 bits)
* `Bytes32`: 32 bytes (256 bits)
* `Bytes40`: 40 bytes (320 bits)
* `Bytes64`: 64 bytes (512 bits)

## Usage

These classes are immutable and enforce their length during instantiation.

```php
use Charcoal\Buffers\Types\Bytes32;

// Must be exactly 32 bytes
$hash = new Bytes32(hash('sha256', 'data', true)); 
```

### Random Data

You can easily generate a fixed-length buffer filled with cryptographically secure random bytes:

```php
$nonce = Bytes16::fromPrng();
```

### Padded Initialization

If your input data is shorter than the required length, you can use `setPadded()` to left-pad it with null bytes (`\0`):

```php
$padded = Bytes32::setPadded("short data");
echo $padded->length(); // 32
```

## Utilities

Fixed-length buffers provide additional helper methods:

* **`isPadded()`**: Returns `true` if the buffer starts with a null byte.
* **`isEmpty()`**: Returns `true` if the buffer consists only of null bytes.
* **`getClean()`**: Returns the content with leading and/or trailing whitespace/null bytes trimmed.

## Custom Fixed-Length Buffers

You can create your own fixed-length buffers by extending the `FixedLengthImmutableBuffer` abstract class:

```php
use Charcoal\Buffers\Abstracts\FixedLengthImmutableBuffer;

final readonly class MyCustomBuffer extends FixedLengthImmutableBuffer
{
    protected const int FixedLengthBytes = 12;
}
```
