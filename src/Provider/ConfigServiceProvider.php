<?php
namespace Ares\Framework\Provider;

use Orion\Core\Exception\InvalidContextException;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Orion\Core\Config;

/**
 * Class ConfigServiceProvider
 *
 * @package Ares\Framework\Provider
 */
class ConfigServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        Config::class
    ];

    /**
     * Registers new service.
     * @throws InvalidContextException
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share(Config::class, function () {
            return new Config(app_dir() . '/configs');
        });
    }
}
