<?php declare(strict_types=1);

namespace Orion\Core\Response\Handler;

use Orion\Core\Response\ResponseType;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Abstract response handler.
 */
abstract class AbstractResponseHandler implements ResponseTypeHandler
{
    /**
     * AbstractResponseHandler constructor.
     *
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(
        protected ResponseFactoryInterface $responseFactory
    ) {}

    /**
     * Get response.
     *
     * @param ResponseType $responseType
     *
     * @return ResponseInterface
     */
    protected function getResponse(ResponseType $responseType): ResponseInterface
    {
        return $responseType->getResponse() ?? $this->responseFactory->createResponse();
    }
}