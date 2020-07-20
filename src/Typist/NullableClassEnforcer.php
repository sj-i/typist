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

use ReflectionClass;

/**
 * @template T
 */
final class NullableClassEnforcer
{
    /** @var class-string[] */
    private static array $registered_classes = [];

    private GeneratedClassEnforcerInterface $enforcer;

    /**
     * @internal
     * @param class-string<T> $passed_class_name
     * @param T|null $value
     * @throws \ReflectionException
     */
    public function __construct(string $passed_class_name, &$value)
    {
        $class = new ReflectionClass($passed_class_name);

        $namespace = $class->getNamespaceName();
        $class_name = $class->getShortName();
        $fully_qualified_class_name = ($namespace === '' ? '\\' : $namespace) . $class_name;

        $enforcer_name = 'Nullable' . $class_name . 'Enforcer';
        $enforcer_namespace = rtrim(implode('\\', [__NAMESPACE__ , $namespace]), '\\');
        $enforcer_interface_name = GeneratedClassEnforcerInterface::class;

        if (!isset(self::$registered_classes[$fully_qualified_class_name])) {
            $code = <<<TEMPLATE
            declare(strict_types=1);
            namespace {$enforcer_namespace};
            class {$enforcer_name} implements \\{$enforcer_interface_name}
            {
                private ?{$fully_qualified_class_name} \$value;
                public function __construct(?{$fully_qualified_class_name} &\$value)
                {
                    \$this->value = &\$value;
                }
            }
            TEMPLATE;
            eval($code);
            self::$registered_classes[$fully_qualified_class_name] = true;
        }

        /** @var class-string<GeneratedClassEnforcerInterface> $enforcer_fqn */
        $enforcer_fqn = implode('\\', [$enforcer_namespace, $enforcer_name]);
        $this->enforcer = new $enforcer_fqn($value);
    }
}
