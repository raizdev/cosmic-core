<?php

namespace Cosmic\Core\Provider;

use Cosmic\Core\Config;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Rakit\Validation\Validator;

/**
 * Class ValidationServiceProvider
 *
 * @package Cosmic\Core\Provider
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
