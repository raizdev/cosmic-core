<?php

namespace Cosmic\Core\Command\Adapters;

interface AdapterInterface
{
    /**
     * Add file
     *
     * @param string $file
     *
     * @return void;
     */
    public function add(string $file);

    /**
     * Minify file
     *
     * @param string $file
     *
     * @return void;
     */
    public function minify(string $file);
}
