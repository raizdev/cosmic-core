<?php

namespace Cosmic\Core\Loader;

use DirectoryIterator;
use Cosmic\Core\Exception\InvalidFileException;

class Directory extends Loader
{
    /**
     * Retrieve the contents of one or more configuration files in a directory
     * and convert them to an array of configuration options. Any invalid files
     * will be silently ignored.
     *
     * @throws \Cosmic\Core\Exception\InvalidFileException
     *
     * @return array Array of configuration options
     */
    public function getArray(): array
    {
        $contents = [];

        foreach (new DirectoryIterator($this->context) as $file) {
            if ($file->isDot()) {
                continue;
            }

            $className = $file->isDir() ? 'Directory' : ucfirst(strtolower($file->getExtension()));
            $classPath = 'PHLAK\\Config\\Loaders\\' . $className;

            $loader = new $classPath($file->getPathname());

            try {
                $contents = array_merge($contents, $loader->getArray());
            } catch (InvalidFileException $e) {
                // Ignore it and continue
            }
        }

        return $contents;
    }
}
