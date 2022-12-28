<?php
namespace Orion\Framework\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Orion\Framework\Config;
use Rakit\Validation\Validator;

/**
 * Class ValidationServiceProvider
 *
 * @package Orion\Framework\Provider
 */
class ValidationServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        Validator::class
    ];

    /**
     * Registers new service.
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(Validator::class, function () use ($container) {
            $config = $container->get(Config::class);
            return new Validator($config->get('api_settings.validation'));
        });
    }
}
