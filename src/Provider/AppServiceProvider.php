<?php
namespace Orion\Core\Provider;

use Orion\Core\Configuration;
use Orion\Core\Factory\AppFactory;
use Jgut\Mapping\Driver\DriverFactoryInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\App;

/**
 * Class AppServiceProvider
 *
 * @package Orion\Core\Provider
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