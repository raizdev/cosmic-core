<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Cosmic\Core\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\App;
use Slim\Interfaces\RouteCollectorInterface;

/**
 * Class RouteCollectorServiceProvider
 *
 * @package Cosmic\Core\Provider
 */
class RouteCollectorServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        RouteCollectorInterface::class
    ];

    /**
     * Registers new service.
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(RouteCollectorInterface::class, function () use ($container) {
            /** @var App $app */
            $app = $container->get(App::class);

            return $app->getRouteCollector();
        });
    }
}