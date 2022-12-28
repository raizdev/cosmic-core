<?php declare(strict_types=1);

namespace Orion\Core\Response\Handler;

use Orion\Core\Response\ResponseType;
use Orion\Core\Response\ViewResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;

/**
 * Twig view renderer response handler.
 */
class TwigViewResponseHandler extends AbstractResponseHandler
{
    /**
     * Twig renderer.
     *
     * @var Twig
     */
    protected $viewRenderer;

    /**
     * TwigViewResponseHandler constructor.
     *
     * @param ResponseFactoryInterface $responseFactory
     * @param Twig                     $viewRenderer
     */
    public function __construct(ResponseFactoryInterface $responseFactory, Twig $viewRenderer)
    {
        parent::__construct($responseFactory);

        $this->viewRenderer = $viewRenderer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function handle(ResponseType $responseType): ResponseInterface
    {
        if (!$responseType instanceof ViewResponse) {
            throw new \InvalidArgumentException(
                \sprintf('Response type should be an instance of %s', ViewResponse::class)
            );
        }

        $responseContent = $this->viewRenderer->fetch($responseType->getTemplate(), $responseType->getParameters());

        $response = $this->getResponse($responseType);
        $response->getBody()->write($responseContent);

        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
}