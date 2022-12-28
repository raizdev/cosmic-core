<?php
namespace Orion\Core\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;

/**
 * Class SessionServiceProvider
 *
 * @package Orion\Core\Provider
 */
class SessionServiceProvider extends AbstractServiceProvider
{
    /**
     * The class that needs to be Provided
     *
     * @var string[]
     */
    protected $provides = [
        Helper::class,
        SessionInterface::class
    ];

    /**
     * Registers our Service Provider
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share(SessionInterface::class, function () use ($container) {
            $session = new PhpSession();
            $session->setOptions([
                'name' => $_ENV['SESSION_NAME'],
                'cache_expire' => $_ENV['SESSION_CACHE_EXPIRE'],
            ]);

            $session->start();
            return $session;
        });

        $container->add(Helper::class, function () use ($container) {
            return new Helper();
        });
    }
}