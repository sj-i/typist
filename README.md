![Minimum PHP version: 7.4.0](https://img.shields.io/badge/php-7.4.0%2B-blue.svg)
[![Packagist](https://img.shields.io/packagist/v/sj-i/typist.svg)](https://packagist.org/packages/sj-i/typist)
[![Packagist](https://img.shields.io/packagist/dt/sj-i/typist.svg)](https://packagist.org/packages/sj-i/typist)
[![Github Actions](https://github.com/sj-i/typist/workflows/build/badge.svg)](https://github.com/sj-i/typist/actions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sj-i/typist/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sj-i/typist/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/sj-i/typist/badge.svg?branch=master)](https://coveralls.io/github/sj-i/typist?branch=master)
![Psalm coverage](https://shepherd.dev/github/sj-i/typist/coverage.svg?)

# Typist

Typist is a PHP library enforcing types of local variables.  
It internally uses references to typed properties introduced in PHP 7.4.

## Installation

```bash
composer require sj-i/typist
```

## Supported Versions

- PHP 7.4 or later

## Usage
### Basic Usage

```php
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

Function interfaces are also available.

```php
use function Typist\int;
use function Typist\float;
use function Typist\string;
use function Typist\bool;
use function Typist\class_; // trailing underscore is needed

$_ = [
    int($typed_int, 1),
    string($typed_string, 'str'),
    bool($typed_bool, false),
    float($typed_float, 0.1),
    class_(\DateTimeInterface::class, $typed_object, new \DateTime()),
];
```

### Nullable Types

```php
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

```php
use Typist\Typist;

$_ = [
    Typist::nullable::int($typed_int, null),
    Typist::nullable::string($typed_string, null),
    Typist::nullable::bool($typed_bool, null),
    Typist::nullable::float($typed_float, null),
    Typist::nullable::class(\DateTimeInterface::class, $typed_object, null),
];
```

Function interfaces are available here too.

```php
use function Typist\nullable_int;
use function Typist\nullable_float;
use function Typist\nullable_string;
use function Typist\nullable_bool;
use function Typist\nullable_class; // trailing underscore is not needed

$_ = [
    nullable_int($typed_int, null),
    nullable_string($typed_string, null),
    nullable_bool($typed_bool, null),
    nullable_float($typed_float, null),
    nullable_class(\DateTimeInterface::class, $typed_object, null),
];
```

## See also
- https://github.com/azjezz/typed
    - similar work by @azjezz
