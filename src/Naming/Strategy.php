<?php declare(strict_types=1);

namespace Orion\Framework\Naming;

/**
 * Route naming strategy.
 */
interface Strategy
{
    /**
     * Combine name parts.
     *
     * @param string[] $nameParts
     *
     * @return string
     */
    public function combine(array $nameParts): string;
}