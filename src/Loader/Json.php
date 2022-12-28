<?php

namespace Orion\Framework\Loader;

use Orion\Framework\Exception\InvalidFileException;

class Json extends Loader
{
    /**
     * Retrieve the contents of a .json file and convert it to an array of
     * configuration options.
     *
     * @throws \Ares\Framework\Exception\InvalidFileException
     *
     * @return array Array of configuration options
     */
    public function getArray(): array
    {
        $contents = file_get_contents($this->context);

        $parsed = json_decode($contents, true);

        if (is_null($parsed)) {
            throw new InvalidFileException('Unable to parse invalid JSON file at ' . $this->context);
        }

        return $parsed;
    }
}
