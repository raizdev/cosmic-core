<?php declare(strict_types=1);

namespace Cosmic\Core\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Generic payload response.
 */
class PayloadResponse extends AbstractResponse
{
    /**
     * Payload.
     *
     * @var array
     */
    protected $payload;

    /**
     * PayloadResponseType constructor.
     *
     * @param mixed                  $payload
     * @param ServerRequestInterface $request
     * @param ResponseInterface|null $response
     */
    public function __construct(
        mixed $payload,
        ServerRequestInterface $request,
        ?ResponseInterface $response = null
    ) {
        parent::__construct($request, $response);

        $this->payload = $payload;
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