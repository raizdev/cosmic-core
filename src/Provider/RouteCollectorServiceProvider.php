<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Cosmic\Core\Provider;

use Cosmic\Core\Response\Handler\JsonResponseHandler;
use Cosmic\Core\Response\Handler\XmlResponseHandler;
use Cosmic\Core\Response\PayloadResponse;
use Cosmic\Core\RouteCollector;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\App;
use Slim\Handlers\Strategies\RequestHandler;
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

            /** @var RouteCollector $routeCollector */
            $routeCollector = $app->getRouteCollector();

            /** @var \Psr\SimpleCache\CacheInterface $cache */
            $cache = new CacheImplementation();

            // Register custom invocation strategy to handle ResponseType objects
            $invocationStrategy = new RequestHandler(
                [
                    PayloadResponse::class => JsonResponseHandler::class,
                ],
                $app->getResponseFactory(),
                $app->getContainer()
            );

            $invocationStrategy->setResponseHandler(PayloadResponse::class, XmlResponseHandler::class);

            $routeCollector->setDefaultInvocationStrategy($invocationStrategy);
            $routeCollector->setCache($cache);
            $routeCollector->registerRoutes();

            return $routeCollector;
        });
    }
}