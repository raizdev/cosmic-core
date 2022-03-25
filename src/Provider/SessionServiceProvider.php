<?php
namespace Cosmic\Core\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use SlimSession\Helper;

/**
 * Class TwigServiceProvider
 *
 * @package Ares\Core\Provider
 */
class SessionServiceProvider extends AbstractServiceProvider
{
    /**
     * The class that needs to be Provided
     *
     * @var string[]
     */
    protected $provides = [
        Helper::class
    ];

    /**
     * Registers our Service Provider
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(Helper::class, function () use ($container) {
            return new Helper();
        });
    }
}