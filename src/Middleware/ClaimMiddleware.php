<?php
namespace Cosmic\Core\Middleware;

use Ares\Framework\Exception\NoSuchEntityException;
use Cosmic\Core\Exception\CoreException;
use Ares\User\Repository\UserRepository;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReallySimpleJWT\Token;

/**
 * Class RolePermissionMiddleware
 *
 * @package Cosmic\Core\Middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * RolePermissionMiddleware constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(
        private SessionInterface $session
    ) {}

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws CoreException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        $authorization = explode(' ', (string) $this->session->get('token'));
        $type          = $authorization[0] ?? '';
        $credentials   = $authorization[1] ?? '';
        $secret        = $_ENV['TOKEN_SECRET'];

        if ($this->session->has('token') && Token::validate($credentials, $secret)) {
            // Append valid token
            $parsedToken = Token::parser($credentials, $secret);
            $request     = $request->withAttribute('token', $parsedToken);

            // Append the user id as request attribute
            $request = $request->withAttribute('cosmic_uid', Token::getPayload($credentials, $secret)['uid']);
        }

        return $handler->handle($request);
    }
}
