<?php

namespace Orion\Core\Loader;

use Orion\Core\Exception\InvalidFileException;
use Yosymfony\Toml\Exception\ParseException;
use Yosymfony\Toml\Toml as TomlParser;

class Toml extends Loader
{
    /**
     * Retrieve the contents of a .toml file and convert it to an array of
     * configuration options.
     *
     * @throws \Orion\Core\Exception\InvalidFileException
     *
     * @return array Array of configuration options
     */
    public function getArray(): array
    {
        try {
            $parsed = TomlParser::parseFile($this->context);
        } catch (ParseException $e) {
            throw new InvalidFileException($e->getMessage());
        }

        return $parsed;
    }
}
