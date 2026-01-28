# TLV (Type-Length-Value) Codec

The TLV codec provides a structured way to encode and decode hierarchical binary data. It uses an **Envelope**
containing multiple **Frames**, where each frame consists of several **Parameters**.

## Structure

* **Envelope**: The top-level container.
* **Frame**: A logical grouping of data within the envelope.
* **Param**: An individual piece of data with a specific type, length, and value.

## Defining a Protocol

To use the TLV codec, you must define a protocol by implementing the `ProtocolDefinitionInterface`. This defines how IDs
are mapped to protocols, frames, and parameter types.

```php
use Charcoal\Buffers\Contracts\Codecs\TLV\ProtocolDefinitionInterface;

class MyProtocolDefinition implements ProtocolDefinitionInterface {
    // Implement mapping methods...
}
```

## Encoding Data

Encoding involves building an `Envelope` structure and passing it to the `TlvBinaryCodec`:

```php
use Charcoal\Buffers\Codecs\TLV\TlvBinaryCodec;
use Charcoal\Buffers\Codecs\TLV\Envelope;
use Charcoal\Buffers\Codecs\TLV\Frame;
use Charcoal\Buffers\Codecs\TLV\Param;

$envelope = new Envelope(
    MyProtocol::Ping,
    new Frame(
        MyFrame::Request,
        new Param(MyParam::Timestamp, time()),
        new Param(MyParam::Payload, "Hello")
    )
);

$binary = TlvBinaryCodec::encode($envelope);
```

## Decoding Data

Decoding converts raw bytes back into an `Envelope` object using your protocol definition:

```php
$bytes = "... binary data ...";
$protocol = new MyProtocolDefinition();

$envelope = TlvBinaryCodec::decode($protocol, $bytes);

echo $envelope->protocol->name; // "Ping"
$firstFrame = $envelope->frames[0];
$firstParam = $firstFrame->params[0];
echo $firstParam->value;
```

## Features

* **Endianness Support**: The protocol determines the byte order for length fields.
* **Validation**: The codec automatically checks for frame caps, parameter caps, and maximum allowed lengths.
* **Trailing Bytes**: You can configure whether the codec should allow or reject unexpected trailing bytes after the
  last frame.
* **Custom Types**: Parameter values are encoded/decoded by their respective types, allowing for custom data
  structures (e.g., nested objects, encrypted strings) within the TLV stream.
