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

final class NullableTypeEnforcer
{
    private function __construct()
    {
    }

    /**
     * @param-out int|null $value_holder
     */
    public static function int(?int &$value_holder, ?int $value): NullableIntEnforcer
    {
        $value_holder = $value;
        return new NullableIntEnforcer($value_holder);
    }

    /**
     * @param-out string|null $value_holder
     */
    public static function string(?string &$value_holder, ?string $value): NullableStringEnforcer
    {
        $value_holder = $value;
        return new NullableStringEnforcer($value_holder);
    }

    /**
     * @param-out bool|null $value_holder
     */
    public static function bool(?bool &$value_holder, ?bool $value): NullableBoolEnforcer
    {
        $value_holder = $value;
        return new NullableBoolEnforcer($value_holder);
    }

    /**
     * @param-out float|null $value_holder
     */
    public static function float(?float &$value_holder, ?float $value): NullableFloatEnforcer
    {
        $value_holder = $value;
        return new NullableFloatEnforcer($value_holder);
    }

    /**
     * @template T
     * @param class-string<T> $class_name
     * @param T|null $value_holder
     * @param T|null $value
     * @return NullableClassEnforcer
     */
    public static function class(string $class_name, &$value_holder, $value): NullableClassEnforcer
    {
        $value_holder = $value;
        return new NullableClassEnforcer($class_name, $value_holder);
    }
}
