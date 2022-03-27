<?php

namespace Cosmic\Core\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Csrf\Guard;

/**
 * Class SessionServiceProvider
 *
 * @package Ares\Core\Provider
 */
class CsrfServiceProvider extends AbstractServiceProvider
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory
    ) {}

    /**
     * The class that needs to be Provided
     *
     * @var string[]
     */
    protected $provides = [
        Guard::class
    ];

    /**
     * Registers our Service Provider
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(Guard::class, function () use ($container) {
            return new Guard($this->responseFactory);
        });
    }
}