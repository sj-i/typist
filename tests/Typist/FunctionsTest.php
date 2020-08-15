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

use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use TypeError;

class FunctionsTest extends TestCase
{
    public function testFunctions()
    {
        $_ = [
            bool($typed_bool1, false),
            int($typed_int1, 1),
            float($typed_float1, 1.1),
            string($typed_string1, 'str'),
            class_(DateTimeInterface::class, $typed_object1, new DateTime()),
            nullable_bool($typed_bool2, false),
            nullable_int($typed_int2, 1),
            nullable_float($typed_float2, 1.1),
            nullable_string($typed_string2, 'str'),
            nullable_class(DateTimeInterface::class, $typed_object2, new DateTime()),
            nullable_bool($typed_bool3, null),
            nullable_int($typed_int3, null),
            nullable_float($typed_float3, null),
            nullable_string($typed_string3, null),
            nullable_class(DateTimeInterface::class, $typed_object3, null),
        ];
        $this->assertFalse($typed_bool1);
        $this->assertFalse($typed_bool2);
        $this->assertNull($typed_bool3);
        $this->assertSame(1, $typed_int1);
        $this->assertSame(1, $typed_int2);
        $this->assertNull($typed_int3);
        $this->assertSame(1.1, $typed_float1);
        $this->assertSame(1.1, $typed_float2);
        $this->assertNull($typed_float3);
        $this->assertSame('str', $typed_string1);
        $this->assertSame('str', $typed_string2);
        $this->assertNull($typed_string3);
        $this->assertInstanceOf(DateTime::class, $typed_object1);
        $this->assertInstanceOf(DateTime::class, $typed_object2);
        $this->assertNull($typed_object3);

        $this->expectException(TypeError::class);
        $typed_int1 = 'a';
    }
}
