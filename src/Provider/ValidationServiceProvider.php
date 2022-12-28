<?php
namespace Orion\Core\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Orion\Core\Config;
use Rakit\Validation\Validator;

/**
 * Class ValidationServiceProvider
 *
 * @package Orion\Core\Provider
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
