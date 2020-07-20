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
 * @psalm-immutable
 */
final class Typist
{
    // phpcs:ignore Generic.NamingConventions.UpperCaseConstantName.ClassConstantNotUpperCase
    public const nullable = NullableTypeEnforcer::class;

    private function __construct()
    {
    }

    /**
     * @param-out bool $value_holder
     */
    public static function bool(?bool &$value_holder, bool $value): BoolEnforcer
    {
        $value_holder = $value;
        return new BoolEnforcer($value_holder);
    }

    /**
     * @param-out int $value_holder
     */
    public static function int(?int &$value_holder, int $value): IntEnforcer
    {
        $value_holder = $value;
        return new IntEnforcer($value_holder);
    }

    /**
     * @param-out float $value_holder
     */
    public static function float(?float &$value_holder, float $value): FloatEnforcer
    {
        $value_holder = $value;
        return new FloatEnforcer($value_holder);
    }

    /**
     * @param-out string $value_holder
     */
    public static function string(?string &$value_holder, string $value): StringEnforcer
    {
        $value_holder = $value;
        return new StringEnforcer($value_holder);
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
    public static function class(string $class_name, &$value_holder, $value): ClassEnforcer
    {
        $value_holder = $value;
        return new ClassEnforcer($class_name, $value_holder);
    }

    /**
     * @return NullableTypeEnforcer|string
     * @psalm-return class-string<NullableTypeEnforcer>
     */
    public static function nullable(): string
    {
        return NullableTypeEnforcer::class;
    }
}
