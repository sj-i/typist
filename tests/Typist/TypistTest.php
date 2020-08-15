<?php

/**
 * This file is part of the sj-i/typist package.
 *
 * (c) sji <sji@sj-i.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Typist;

use PHPUnit\Framework\TestCase;
use Throwable;
use TypeError;

class TypistTest extends TestCase
{
    public function testBool(): void
    {
        $_ = [
            Typist::bool($typed_value, false),
        ];
        $this->assertFalse($typed_value);
        $typed_value = true;
        $this->assertTypeError(function () use (&$typed_value) {
            $typed_value = 1;
        });
        $this->assertTypeError(function () use (&$typed_value) {
            $typed_value = null;
        });
    }

    public function testInt(): void
    {
        $_ = [
            Typist::int($typed_value, 1),
        ];
        $this->assertSame(1, $typed_value);
        $typed_value = 2;
        $this->assertTypeError(function () use (&$typed_value) {
            $typed_value = 'aaa';
        });
        $this->assertTypeError(function () use (&$typed_value) {
            $typed_value = null;
        });
    }

    public function testFloat(): void
    {
        $_ = [
            Typist::float($typed_value, 1.1),
        ];
        $this->assertSame(1.1, $typed_value);
        $typed_value = -1.1;
        $typed_value = 1;
        $this->assertTypeError(function () use (&$typed_value) {
            $typed_value = 'a';
        });
        $this->assertTypeError(function () use (&$typed_value) {
            $typed_value = null;
        });
    }

    public function testString(): void
    {
        $_ = [
            Typist::string($typed_value, 'a'),
        ];
        $this->assertSame('a', $typed_value);
        $typed_value = 'b';
        $this->assertTypeError(function () use (&$typed_value) {
            $typed_value = 1;
        });
        $this->assertTypeError(function () use (&$typed_value) {
            $typed_value = null;
        });
    }

    public function testClass(): void
    {
        $_ = [
            Typist::class(\DateTimeInterface::class, $typed_value, new \DateTimeImmutable()),
        ];
        $this->assertInstanceOf(\DateTimeImmutable::class, $typed_value);
        $typed_value = new \DateTime();
        $this->assertTypeError(function () use (&$typed_value) {
            $typed_value = 'a';
        });
        $this->assertTypeError(function () use (&$typed_value) {
            $typed_value = null;
        });
    }

    public function testNullableBool(): void
    {
        $_ = [
            Typist::nullable()::bool($typed_value, false),
        ];
        $this->assertFalse($typed_value);
        $typed_value = true;
        $typed_value = null;
        $this->expectException(TypeError::class);
        $typed_value = 1;
    }

    public function testNullableInt(): void
    {
        $_ = [
            Typist::nullable()::int($typed_value, 1),
        ];
        $this->assertSame(1, $typed_value);
        $typed_value = 2;
        $typed_value = null;
        $this->expectException(TypeError::class);
        $typed_value = 'aaa';
    }

    public function testNullableFloat(): void
    {
        $_ = [
            Typist::nullable()::float($typed_value, 1.1),
        ];
        $this->assertSame(1.1, $typed_value);
        $typed_value = 1;
        $typed_value = -1.1;
        $typed_value = null;
        $this->expectException(TypeError::class);
        $typed_value = '';
    }

    public function testNullableString(): void
    {
        $_ = [
            Typist::nullable()::string($typed_value, 'a'),
        ];
        $this->assertSame('a', $typed_value);
        $typed_value = 'b';
        $typed_value = null;
        $this->expectException(TypeError::class);
        $typed_value = 1;
    }

    public function testNullableClass(): void
    {
        $_ = [
            Typist::nullable()::class(
                \DateTimeInterface::class,
                $typed_value,
                new \DateTimeImmutable()
            ),
        ];
        $this->assertInstanceOf(\DateTimeImmutable::class, $typed_value);
        $typed_value = new \DateTime();
        $typed_value = null;
        $this->assertTypeError(function () use (&$typed_value) {
            $typed_value = 'a';
        });
    }

    private function assertTypeError(callable $func): void
    {
        try {
            $func();
        } catch (Throwable $e) {
        }
        $this->assertTrue(isset($e));
        $this->assertInstanceOf(TypeError::class, $e);
    }
}
