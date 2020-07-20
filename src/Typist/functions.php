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

/**
 * @param-out bool $value_holder
 */
function bool(?bool &$value_holder, bool $value): BoolEnforcer
{
    return Typist::bool($value_holder, $value);
}

/**
 * @param-out int $value_holder
 */
function int(?int &$value_holder, int $value): IntEnforcer
{
    return Typist::int($value_holder, $value);
}

/**
 * @param-out float $value_holder
 */
function float(?float &$value_holder, float $value): FloatEnforcer
{
    return Typist::float($value_holder, $value);
}

/**
 * @param-out string $value_holder
 */
function string(?string &$value_holder, string $value): StringEnforcer
{
    return Typist::string($value_holder, $value);
}

/**
 * @template T
 * @param class-string<T> $class_name
 * @param T|null $value_holder
 * @param T $value
 * @param-out T $value_holder
 * @return ClassEnforcer
 * @throws \ReflectionException
 */
function class_(string $class_name, &$value_holder, $value): ClassEnforcer
{
    return Typist::class($class_name, $value_holder, $value);
}

/**
 * @param-out bool|null $value_holder
 */
function nullable_bool(?bool &$value_holder, ?bool $value): NullableBoolEnforcer
{
    return Typist::nullable()::bool($value_holder, $value);
}

/**
 * @param-out int|null $value_holder
 */
function nullable_int(?int &$value_holder, ?int $value): NullableIntEnforcer
{
    return Typist::nullable()::int($value_holder, $value);
}

/**
 * @param-out float|null $value_holder
 */
function nullable_float(?float &$value_holder, ?float $value): NullableFloatEnforcer
{
    return Typist::nullable()::float($value_holder, $value);
}

/**
 * @param-out string|null $value_holder
 */
function nullable_string(?string &$value_holder, ?string $value): NullableStringEnforcer
{
    return Typist::nullable()::string($value_holder, $value);
}

/**
 * @template T
 * @param class-string<T> $class_name
 * @param T|null $value_holder
 * @param T|null $value
 * @return NullableClassEnforcer
 */
function nullable_class(string $class_name, &$value_holder, $value): NullableClassEnforcer
{
    return Typist::nullable()::class($class_name, $value_holder, $value);
}
