<?php

namespace Orion\Framework\Loader;

use Orion\Framework\Exception\InvalidFileException;

class Php extends Loader
{
    /**
     * Retrieve the contents of a .php configuration file and convert it to an
     * array of configuration options.
     *
     * @throws \Orion\Framework\Exception\InvalidFileException
     *
     * @return array Array of configuration options
     */
    public function getArray(): array
    {
        $contents = include $this->context;

        if (gettype($contents) != 'array') {
            throw new InvalidFileException($this->context . ' does not return a valid array');
        }

        return $contents;
    }
}
