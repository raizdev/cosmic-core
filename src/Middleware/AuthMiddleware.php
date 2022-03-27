<?php
namespace Cosmic\Core\Middleware;

use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

/**
 * Class AuthMiddleware
 *
 * @package Cosmic\Core\Middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * RolePermissionMiddleware constructor.
     *
     * @param ResponseFactoryInterface $responseFactory
     * @param SessionInterface $session
     */
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private SessionInterface $session
    ) {}

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        if($this->session->get('token')) {
           return $handler->handle($request);
        }

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->responseFactory->createResponse()
            ->withStatus(302)
            ->withHeader('Location', $routeParser->urlFor('login'));

    }
}
