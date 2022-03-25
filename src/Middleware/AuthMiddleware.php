<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Core\Middleware;

use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Core\Exception\CoreException;
use Ares\User\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SlimSession\Helper as SessionHelper;

/**
 * Class RolePermissionMiddleware
 *
 * @package Ares\Core\Middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * RolePermissionMiddleware constructor.
     *
     * @param SessionHelper $sessionHelper
     */
    public function __construct(
        private UserRepository $userRepository,
        private SessionHelper $sessionHelper
    ) {}

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws CoreException|NoSuchEntityException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        if($this->sessionHelper->get('user')) {
            $user = $this->userRepository->get($this->sessionHelper->get('user', 'user_id'), 'id', true);
        }

        return $handler->handle($request);
    }
}
