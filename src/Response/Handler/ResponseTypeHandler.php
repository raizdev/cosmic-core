<?php declare(strict_types=1);
namespace Orion\Framework\Response\Handler;

use Orion\Framework\Response\ResponseType;
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