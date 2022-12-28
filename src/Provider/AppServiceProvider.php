<?php
namespace Orion\Framework\Provider;

use Orion\Framework\Configuration;
use Orion\Framework\Factory\AppFactory;
use Jgut\Mapping\Driver\DriverFactoryInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\App;

/**
 * Class AppServiceProvider
 *
 * @package Orion\Framework\Provider
 */
class AppServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        App::class
    ];

    /**
     * Registers new service.
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share(App::class, function () use ($container) {
            $configuration = new Configuration([
                'sources' => [
                    [
                        "path" => src_dir(),
                        "type" => DriverFactoryInterface::DRIVER_ANNOTATION
                    ],
                ],
            ]);

            AppFactory::setContainer($container);
            AppFactory::setRouteCollectorConfiguration($configuration);

            return AppFactory::create();
        });
    }
}