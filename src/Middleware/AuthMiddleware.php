<?php
namespace Orion\Core\Middleware;

use Orion\Core\Exception\AuthenticationException;
use Orion\Core\Interfaces\CustomResponseCodeInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReallySimpleJWT\Token;

/**
 * JWT Auth middleware.
 *
 * @package Orion\Core\Middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Auth constructor.
     *
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory
    ) {}

    /**
     * Process an incoming server request.
     *
     * @param   ServerRequestInterface   $request  The request
     * @param   RequestHandlerInterface  $handler  The handler
     *
     * @return ResponseInterface The response
     * @throws AuthenticationException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $token = explode(' ', (string) $request->getHeaderLine('Authorization'))[1] ?? '';

        if (!$token || !Token::validate($token, $_ENV['TOKEN_SECRET'])) {
            $this->responseFactory
                ->createResponse()
                ->withHeader('Content-Type', 'application/problem+json');

            throw new AuthenticationException(
                __('You arent allowed to visit this site'),
                CustomResponseCodeInterface::RESPONSE_NOT_ALLOWED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNAUTHORIZED
            );
        }

        return $handler->handle($request);
    }
}
