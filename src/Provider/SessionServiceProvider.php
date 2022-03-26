<?php

namespace Cosmic\Core\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;

/**
 * Class SessionServiceProvider
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

            return $session;
        });
    }
}