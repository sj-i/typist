# Typist

![Minimum PHP version: 7.4.0](https://img.shields.io/badge/php-7.4.0%2B-blue.svg)
[![Packagist](https://img.shields.io/packagist/v/sj-i/typist.svg)](https://packagist.org/packages/sj-i/typist)
[![Packagist Downloads](https://img.shields.io/packagist/dt/sj-i/typist.svg)](https://packagist.org/packages/sj-i/typist/stats)
[![Github Actions](https://github.com/sj-i/typist/workflows/build/badge.svg)](https://github.com/sj-i/typist/actions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sj-i/typist/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sj-i/typist/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/sj-i/typist/badge.svg?branch=master)](https://coveralls.io/github/sj-i/typist?branch=master)
[![Psalm coverage](https://shepherd.dev/github/sj-i/typist/coverage.svg?)](https://shepherd.dev/github/sj-i/typist)

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

## How it works
If you have ever read [the RFC of Typed Properties](https://wiki.php.net/rfc/typed_properties_v2), especially its section describing [how it works with references](https://wiki.php.net/rfc/typed_properties_v2#references), you might not have anything unclear.

So I try to put a bit of explanation here, with the readers who haven't read yet in mind.

### References in PHP
Firstly, please see the following code.

```php
$a = 1;
$b =& $a;
$c =& $b;
```

Let's illustrate this code step by step, line by line.

#### First line
```php
$a = 1;
```

The first line can be illustrated as below.

![php_reference_a](https://user-images.githubusercontent.com/6488121/88196615-5efef680-cc7c-11ea-9732-78da3f36ca6c.png)

There is a variable named `$a`, and its value is `1`.  
That's all, very simple.

#### Second line

```php
$b =& $a;
```

Then the code added the second line can be illustrated as below.

![php_reference_ab](https://user-images.githubusercontent.com/6488121/88196703-763de400-cc7c-11ea-9c51-08114ff1edeb.png)

There are two variables named `$a` and `$b`.  
They are just different names indicating the same data, which is `1` in this case.
The relationship is not like "$a is a variable which contains 1, and $b is a pointer to $a".
The functionality of the reference assignment operator `=&` is just creating another name of data.

#### Third line

```php
$c =& $b;
```

One more reference assignment creates one more name. That's it. The final state can be illustrated as below.

![php_reference_abc](https://user-images.githubusercontent.com/6488121/88196759-848c0000-cc7c-11ea-864a-86f18ad1889b.png)

So there can be a group that consists of multiple names indicating the same data.
Any modifications via any names affect the result of reading via any other names in the group.
The RFC of the Typed Properties calls them "reference set".

### Typed Properties and References

Then, what happens if a reference set contains typed properties?

![php_reference_abc_typed](https://user-images.githubusercontent.com/6488121/88196910-b604cb80-cc7c-11ea-9170-d9b6731d79a5.png)

Now it's about properties, so using `$a` or `$b` as an example is not appropriate anymore.
This time I change the code as below.

```php
$o = new class() {
    public int $a = 1;
    public float $b;
};
$o->b =& $o->a;
$c =& $o->b;
```

It can be re-illustrated like this.

![php_reference_typed_properties_ab_local_variable_c](https://user-images.githubusercontent.com/6488121/88196916-b8672580-cc7c-11ea-935c-a1f51033c7e2.png)

So let's try to assign some values to `$o->a`.

```php
$o->a = 1; // legal
$o->a = 'abc'; // TypeError will be thrown. $o->a is declared as int
```

It's easy and understandable results.

On the other hand, what about the example below?

```php
$o->b = 1.5; // $o->b is declared as float, but...
var_dump($o->a); // $o->a is declared as int, so this must not be `1.5`!
```

And what about the example below?

```php
$c = new DateTime(); // $c is a local variable, so doesn't have any type constraint itself, but...
var_dump($o->a, $o->b); // Neither $o->a and $o->b must not be a DateTime!!!
```

If a reference set contains typed properties, all types in the set must be satisfied on its assignment. The interpreter actually checks each property type in that situation and throws `\TypeError` on error.

Here you have seen how a local variable can be a typed variable. A local variable within a reference set that contains typed properties is checked with its type.

You can get such local variables in two ways. One is reference assignment **from** typed properties, and another is reference assignment **to** typed properties.

### Reference assignment from or to, returning reference or pass-by-reference
Reference assignment from typed properties can make typed local variables. Though I haven't chosen this way, I think this method is intuitive because what we are trying is virtually to extract the ability of type checking from typed properties.

I have seen two instances of this method.

- [Poor Man's Typed Variables](https://www.slideshare.net/nikita_ppv/typed-properties-and-more-whats-coming-in-php-74) from the slides by @nikic
- [azjezz/typed](https://github.com/azjezz/typed) by @azjezz

The implementation of them could be summarized as below.

- Define functions like below for each type.
    - Instantiate object which has a property with requested type.
    - Grab the object in the global state, because the object will be GCed otherwise.
    - Return reference to the typed property.

Both of them leak memory because each creation of typed variables put the reference to the typed property object in the global state. azjezz/typed provides [methods to free the references manually](https://github.com/azjezz/typed/blob/426a075e834d41383010a6fe51b073b848fb7d3c/src/Typed/functions.php#L284-L322).

There are two things I don't like about this method.

1. Memory leak (or manual memory management)
2. Forcing reference assignment in user code
    - using `&=` everywhere is **somehow not pretty!**
    - yeah, it's **just my preference**!!!!

As you can see from the code in this repository, problem 2 can be solved by using pass-by-reference.  
Reference assignment **from** a typed property makes a reference set containing a typed property.  
Reference assignment **to** a typed property also makes a reference set containing a typed property.

Then the return value can be used for something else.  
By returning typed property object (this is called Enforcer in this library) and grab it by a local variable in the caller, problem 1 can be solved. The variable is only used to keep refcount of Enforcer, so an inconspicuous name like `$_` would be good. 

### Use with psalm
[Psalm](https://psalm.dev/) emits warning [when the type of a pass-by-reference variable is changed](https://psalm.dev/r/110e4397a2).
So if you use this library, the type of local variable can also be checked statically.
And more, `$_` is [ignored](https://github.com/vimeo/psalm/issues/540#issuecomment-368156152) by UnusedVariable checks.
