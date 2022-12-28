<?php
namespace Orion\Framework\Middleware;

use Orion\Framework\Model\Locale;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class LocaleMiddleware
 *
 * @package Orion\Framework\Middleware
 */
class LocaleMiddleware implements MiddlewareInterface
{
    /** @var string */
    private const FALLBACK_LOCALE = 'en';

    /** @var string */
    private const LOCALE_PATH_KEY = 0;

    /**
     * LocaleMiddleware constructor.
     *
     * @param Locale $locale
     */
    public function __construct(
        private readonly Locale $locale
    ) {}

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $locale = $this->getLocale($request);

        $this->locale->setLocale($locale);
        $this->locale->setFallbackLocale(self::FALLBACK_LOCALE);

        return $handler->handle($request);
    }

    /**
     * Returns locale of path.
     *
     * @param ServerRequestInterface $request
     * @return string
     */
    private function getLocale(ServerRequestInterface $request): string
    {
        $path = ltrim($request->getUri()->getPath(), '/');
        $splittedPath = explode('/', $path);

        return $splittedPath[self::LOCALE_PATH_KEY];
    }
}
