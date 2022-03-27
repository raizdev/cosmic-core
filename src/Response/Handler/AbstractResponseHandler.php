<?php declare(strict_types=1);

namespace Cosmic\Core\Response\Handler;

use Cosmic\Core\Response\ResponseType;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Abstract response handler.
 */
abstract class AbstractResponseHandler implements ResponseTypeHandler
{
    /**
     * @var ResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * AbstractResponseHandler constructor.
     *
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

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