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

final class FloatEnforcer
{
    private float $value;

    /**
     * @internal
     */
    public function __construct(float &$value)
    {
        $this->value = &$value;
    }
}
