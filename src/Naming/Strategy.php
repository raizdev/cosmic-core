<?php declare(strict_types=1);

namespace Cosmic\Core\Naming;

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