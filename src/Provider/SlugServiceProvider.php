<?php
namespace Orion\Core\Provider;

use Cocur\Slugify\Slugify;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Orion\Core\Config;

/**
 * Class SlugServiceProvider
 *
 * @package Orion\Core\Provider
 */
class SlugServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        Slugify::class
    ];

    /**
     * Registers new service.
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(Slugify::class, function () use ($container) {
            $config = $container->get(Config::class);
            return new Slugify([
                'trim' => $config->get('api_settings.slug.trim')
            ]);
        });
    }
}
