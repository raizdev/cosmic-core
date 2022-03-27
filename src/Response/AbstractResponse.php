<?php declare(strict_types=1);

namespace Cosmic\Core\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Abstract response.
 */
abstract class AbstractResponse implements ResponseType
{
    /**
     * PSR-7 request.
     *
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * PSR-7 response.
     *
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * AbstractResponse constructor.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface|null $response
     */
    public function __construct(ServerRequestInterface $request, ?ResponseInterface $response = null)
    {
        $this->request = $request;
        $this->response = $response;
    }

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