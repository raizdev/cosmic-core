<?php

namespace Cosmic\Core\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Odan\Session\SessionInterface;
use Cosmic\Core\Extension\DateExtension;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigRuntimeLoader;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\TwigFunction;
use Cosmic\Core\Config;

/**
 * Class TwigServiceProvider
 *
 * @package Cosmic\Core\Provider
 */
class TwigServiceProvider extends AbstractServiceProvider
{
    /**
     * The class that needs to be Provided
     *
     * @var string[]
     */
    protected $provides = [
        Twig::class
    ];

    /**
     * Registers our Service Provider
     *
     * @throws LoaderError
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(Twig::class, function () use ($container) {
            /*** @var $app App */
            $app = $container->get(App::class);

            $twig = Twig::create(src_dir() . '/',
                ['cache' => ($_ENV['CACHE_ENABLED'] === false) ? cache_dir() . '/twig' : false]);

            $twig->addRuntimeLoader(
                new TwigRuntimeLoader(
                    $app->getRouteCollector()->getRouteParser(),
                    (new \Slim\Psr7\Factory\UriFactory)->createFromGlobals($_SERVER)
                )
            );

            $twig->addExtension(new DateExtension());

            $this->registerGlobals($twig->getEnvironment());
            $this->registerFunctions($twig->getEnvironment());

            return $twig;
        });
    }

    /**
     * @param $string , $placeholders
     * @return string
     */
    public function getLanguage($string, $placeholders = []): string
    {
        return __($string, $placeholders);
    }

    /**
     * @param $string
     * @return string
     */
    public function getConfig($string): string | null
    {
        $container = $this->getContainer();
        $config = $container->get(Config::class);

        return $config->get($string);
    }

    /**
     * @param Environment $twig
     * @return void
     */
    private function registerFunctions(Environment $twig): void
    {
        $twig->addFunction(
            new TwigFunction('lang', [$this, 'getLanguage'])
        );
        $twig->addFunction(
            new TwigFunction('config', [$this, 'getConfig'])
        );
    }

    /**
     * @param Environment $twig
     * @return void
     */
    private function registerGlobals(Environment $twig)
    {
        $container = $this->getContainer();

        /** @var SessionInterface $session */
        $session = $container->get(SessionInterface::class);

        $twig->addGlobal(
            'ajaxRequest', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
        );

        $twig->addGlobal(
            'user', $session->get('user')
        );
    }
}
