<?php
namespace Orion\Core\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * Class BodyParserMiddleware
 *
 * @package Orion\Core\Middleware
 */
class BodyParserMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * @param   Request         $request
     * @param   RequestHandler  $handler
     *
     * @return Response
     * @throws \JsonException
     */
    public function process(
        Request $request,
        RequestHandler $handler
    ): Response {
        $contentType = $request->getHeaderLine('Content-Type');

        if (str_contains($contentType, 'application/json')) {
            $contents = json_decode(
                @file_get_contents('php://input'),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            if (json_last_error() === JSON_ERROR_NONE) {
                $request = $request->withParsedBody($contents);
            }
        }

        $response = $handler->handle($request);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
