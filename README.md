# Typist

Typist is a PHP library enforcing types of local variables.  
It internally uses references to typed properties introduced in PHP 7.4.

## Installation

```
composer require sj-i/typist
```

## Supported Versions

- PHP 7.4 or later

# Usage
## Basic Usage

```
use Typist\Typist;

// type enforcements are valid during the lifetime of this `$_`
$_ = [
    Typist::int($typed_int, 1),
    Typist::string($typed_string, 'str'),
    Typist::bool($typed_bool, false),
    Typist::float($typed_float, 0.1),
    Typist::class(\DateTimeInterface::class, $typed_object, new \DateTime()),
];

assert($typed_int === 1);
assert($typed_string === 'str');
assert($typed_bool === false);
assert($typed_float === 0.1);
assert($typed_object instanceof \DateTime);

// modifications with valid types are OK
$typed_int = 2;
$typed_string = 'trs';
$typed_bool = true;
$typed_float = -0.1;
$typed_object = new DateTimeImmutable();

// any statements below raises TypeError
$typed_int = 'a';
$typed_string = 1;
$typed_bool = 'a';
$typed_float = 'a';
$typed_object = 'a';
```

## Nullable Types

```
use Typist\Typist;

$_ = [
    Typist::nullable()::int($typed_int1, 1),
    Typist::nullable()::int($typed_int2, null),
    Typist::nullable()::string($typed_string1, 'str'),
    Typist::nullable()::string($typed_string2, null),
    Typist::nullable()::bool($typed_bool1, false),
    Typist::nullable()::bool($typed_bool2, null),
    Typist::nullable()::float($typed_float1, 0.1),
    Typist::nullable()::float($typed_float2, null),
    Typist::nullable()::class(\DateTimeInterface::class, $typed_object1, new \DateTime()),
    Typist::nullable()::class(\DateTimeInterface::class, $typed_object2, null),
];
```

or if you use PHP8, `()` can be omitted.

```
use Typist\Typist;

$_ = [
    Typist::nullable::int($typed_int2, null),
    Typist::nullable::string($typed_string2, null),
    Typist::nullable::bool($typed_bool2, null),
    Typist::nullable::float($typed_float2, null),
    Typist::nullable::class(\DateTimeInterface::class, $typed_object2, null),
];
```
