<?php
namespace Orion\Framework\Interfaces;

/**
 * Interface Loadable
 *
 * @package Orion\Framework\Interfaces
 */
interface Loadable
{
    /**
     * Retrieve the contents of one or more configuration files and convert them
     * to an array of configuration options.
     *
     * @return array Array of configuration options
     */
    public function getArray(): array;
}
