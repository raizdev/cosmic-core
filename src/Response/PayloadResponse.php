<?php declare(strict_types=1);
namespace AreOrions\Framework\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Generic payload response.
 */
class PayloadResponse extends AbstractResponse
{
    /**
     * PayloadResponseType constructor.
     *
     * @param mixed                  $payload
     * @param ServerRequestInterface $request
     * @param ResponseInterface|null $response
     */
    public function __construct(
        protected mixed $payload,
        ServerRequestInterface $request,
        ?ResponseInterface $response = null
    ) {
        parent::__construct($request, $response);
    }

    /**
     * Get payload.
     *
     * @return mixed
     */
    public function getPayload(): mixed
    {
        return $this->payload;
    }
}