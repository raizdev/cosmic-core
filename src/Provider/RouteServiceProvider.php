<?php
namespace Orion\Framework\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;

/**
 * Class RouteServiceProvider
 *
 * @package Orion\Framework\Provider
 */
class RouteServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        ResponseFactoryInterface::class
    ];

    /**
     * Registers new service.
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(ResponseFactoryInterface::class, function () use ($container) {
            return $container->get(App::class)->getResponseFactory();
        });
    }
}
