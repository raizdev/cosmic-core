<?php declare(strict_types=1);

namespace Cosmic\Core\Strategy;

use Cosmic\Core\Response\Handler\ResponseTypeHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RequestHandlerInvocationStrategyInterface;

/**
 * Route callback strategy with route parameters as individual arguments.
 */
class RequestResponseArgs implements RequestHandlerInvocationStrategyInterface
{
    use ResponseTypeStrategyTrait;

    /**
     * RequestResponseArgs constructor.
     *
     * @param array<string, string|ResponseTypeHandler> $responseHandlers
     * @param ResponseFactoryInterface                  $responseFactory
     * @param ContainerInterface|null                   $container
     */
    public function __construct(
        array $responseHandlers,
        ResponseFactoryInterface $responseFactory,
        ?ContainerInterface $container = null
    ) {
        $this->setResponseHandlers($responseHandlers);

        $this->responseFactory = $responseFactory;
        $this->container = $container;
    }

    /**
     * Invoke a route callable that implements RequestHandlerInterface.
     *
     * @param callable               $callable
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param mixed[]                $routeArguments
     *
     * @return ResponseInterface
     */
    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    ): ResponseInterface {
        return $this->handleResponse($callable($request, $response, ...\array_values($routeArguments)));
    }
}