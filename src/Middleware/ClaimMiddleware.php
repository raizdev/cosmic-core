<?php
namespace Orion\Core\Middleware;

use Orion\Framework\Exception\NoSuchEntityException;
use Cosmic\Core\Exception\CoreException;
use Orion\User\Repository\UserRepository;
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
class ClaimMiddleware implements MiddlewareInterface
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
        $credentials   = $authorization[0] ?? '';
        $secret        = $_ENV['TOKEN_SECRET'];

        if ($this->session->has('token') && Token::validate($credentials, $secret)) {

            // Append valid token
            $parsedToken = Token::parser($credentials, $secret);
            $request     = $request->withAttribute('token', $parsedToken);

            // Append the user id as request attribute
            $request = $request->withAttribute('cosmic_uid', Token::getPayload($credentials, $secret)['uid']);
            $this->session->set('user', user($request));

        }

        return $handler->handle($request);
    }
}