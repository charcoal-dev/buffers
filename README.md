# Charcoal Buffers

![Tests](https://github.com/charcoal-dev/buffers/actions/workflows/tests.yml/badge.svg)
[![MIT License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

Buffers is a library designed for the intricate handling of byte arrays. Within its suite of tools, you'll find
capabilities that not only simplify the generation of cryptographic hashes and seamless byte array reading but also
provide robust solutions for fixed-sized buffer frames and variable length buffers. Furthermore, understanding the
significance of byte order, our library offers comprehensive support for both little-endian and big-endian
configurations, ensuring optimal adaptability and interoperability across diverse platforms and systems. Crafted with
precision and emphasizing user experience, this library offers an unparalleled bridge between complexity and usability
in the byte array manipulation arena.

For detailed information, guidance, and setup instructions regarding this library, please refer to our official
documentation website:

[https://charcoal.dev/lib/buffers](https://charcoal.dev/lib/buffers)

## Features

Explore the library's features through the following guides:

* **[Buffer Management](docs/buffer-management.md)**: Learn about mutable (`Buffer`) and immutable (`BufferImmutable`)
  buffers, integer writing, and basic manipulation.
* **[Byte Reader](docs/byte-reader.md)**: Understand how to use the sequential reader for parsing binary data.
* **[Endianness and Byte Order](docs/byte-order.md)**: Guides on handling different byte orders (LE/BE), host
  architecture detection, and integer packing.
* **[TLV (Type-Length-Value) Codec](docs/tlv-codec.md)**: Documentation on the structured binary encoding and decoding
  system.
* **[Fixed-Length Types](docs/fixed-length-types.md)**: Overview of specialized buffers for fixed-size data like
  cryptographic hashes or identifiers.

## Installation

You can install the library via Composer:

```bash
composer require charcoal-dev/buffers
```

