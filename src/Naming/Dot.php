<?php declare(strict_types=1);

namespace Orion\Core\Naming;

/**
 * Dot separated route naming strategy.
 */
class Dot implements Strategy
{
    /**
     * {@inheritdoc}
     */
    public function combine(array $nameParts): string
    {
        return \implode('.', $nameParts);
    }
}