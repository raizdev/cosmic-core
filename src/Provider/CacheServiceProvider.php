<?php
namespace Orion\Framework\Provider;

use Phpfastcache\Config\ConfigurationOption;
use Phpfastcache\Drivers\Predis\Config as PredisConfig;
use Phpfastcache\Helper\Psr16Adapter as FastCache;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Class CacheServiceProvider
 *
 * @package Ares\Framework\Provider
 */
class CacheServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        FastCache::class
    ];

    /**
     * Registers new service.
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share(FastCache::class, function () use ($container) {

            if ($_ENV['CACHE_TYPE'] == 'Predis') {
                $configurationOption = new PredisConfig([
                    'host' => $_ENV['CACHE_REDIS_HOST'],
                    'port' => (int) $_ENV['CACHE_REDIS_PORT']
                ]);

                return new FastCache($_ENV['CACHE_TYPE'], $configurationOption);
            }

            $configurationOption = new ConfigurationOption([
                'path' => cache_dir() . '/filecache'
            ]);

            return new FastCache($_ENV['CACHE_TYPE'], $configurationOption);
        });
    }
}
