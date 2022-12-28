<?php declare(strict_types=1);
namespace Orion\Framework\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Response type interface.
 */
interface ResponseType
{
    /**
     * Get PSR-7 request.
     *
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface;

    /**
     * Get PSR-7 response.
     *
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface;
}