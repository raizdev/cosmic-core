<?php declare(strict_types=1);
namespace Orion\Core\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Abstract response.
 */
abstract class AbstractResponse implements ResponseType
{
    /**
     * AbstractResponse constructor.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface|null $response
     */
    public function __construct(
        private readonly ServerRequestInterface $request,
        private readonly ?ResponseInterface $response = null
    ) {}

    /**
     * {@inheritdoc}
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}