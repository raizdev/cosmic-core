<?php declare(strict_types=1);

namespace Orion\Core\Response\Handler;

use Orion\Core\Response\ResponseType;
use Psr\Http\Message\ResponseInterface;

/**
 * Response type handler.
 */
interface ResponseTypeHandler
{
    /**
     * Handle response.
     *
     * @param ResponseType $responseType
     *
     * @return ResponseInterface
     */
    public function handle(ResponseType $responseType): ResponseInterface;
}