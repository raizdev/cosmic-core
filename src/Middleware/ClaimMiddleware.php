<?php
namespace Orion\Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReallySimpleJWT\Token;

/**
 * ClaimMiddleware.
 *
 * @package Orion\Core\Middleware
 */
class ClaimMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * @param   ServerRequestInterface   $request  The request
     * @param   RequestHandlerInterface  $handler  The handler
     *
     * @return ResponseInterface The response
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $authorization = explode(' ', $request->getHeaderLine('Authorization'));
        $type          = $authorization[0] ?? '';
        $credentials   = $authorization[1] ?? '';
        $secret        = $_ENV['TOKEN_SECRET'];

        if ($type === 'Bearer' && Token::validate($credentials, $secret)) {
            // Append valid token
            $parsedToken = Token::parser($credentials, $secret);
            $request     = $request->withAttribute('token', $parsedToken);

            // Append the user id as request attribute
            $request = $request->withAttribute('ares_uid', Token::getPayload($credentials, $secret)['uid']);
        }

        return $handler->handle($request);
    }
}
