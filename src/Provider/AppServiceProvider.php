<?php

namespace Cosmic\Core\Provider;

use Cosmic\Core\Configuration;
use Cosmic\Core\Factory\AppFactory;
use Cosmic\Core\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Jgut\Mapping\Driver\AbstractAnnotationDriver;
use Jgut\Mapping\Driver\DriverFactoryInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PHLAK\Config\Config;
use Slim\App;

/**
 * Class AppServiceProvider
 *
 * @package Cosmic\Core\Provider
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
            /** @var Config $config */
            $config = $container->get(Config::class);

            $sourceArray = [];
            $sources = $config->get('route_settings.resolvers');

            foreach($sources as $source) {
                $sourceArray[] = src_dir() . "/" . $source;
            }

            $configuration = new Configuration([
                'sources' => [
                    [
                        "path" => $sourceArray,
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