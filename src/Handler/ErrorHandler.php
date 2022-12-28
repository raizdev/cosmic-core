<?php
namespace Orion\Framework\Handler;

use Ares\Framework\Exception\BaseException;
use Ares\Framework\Interfaces\CustomResponseCodeInterface;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Framework\Model\CustomResponse as CustomResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

/**
 * Class ErrorHandler
 *
 * @package Ares\Framework\Handler
 */
class ErrorHandler implements ErrorHandlerInterface
{
    /**
     * ErrorHandler constructor.
     *
     * @param ResponseFactoryInterface $responseFactory
     * @param CustomResponse           $customResponse
     * @param LoggerInterface          $logger
     */
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private CustomResponse $customResponse,
        private readonly LoggerInterface $logger
    ) {}

    /**
     * Catches exception and returns it in json format.
     *
     * @param ServerRequestInterface $request
     * @param Throwable $exception
     * @param bool $displayErrorDetails
     * @param bool $logErrors
     * @param bool $logErrorDetails
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        if (!$exception instanceof BaseException) {
            $statusCode = $exception->getCode() ?: HttpResponseCodeInterface::HTTP_RESPONSE_INTERNAL_SERVER_ERROR;
        } else {
            $statusCode = $exception->getCustomCode() ?: CustomResponseCodeInterface::RESPONSE_UNKNOWN_ERROR;
        }

        $customResponse = response()
            ->setStatus('error')
            ->setCode($statusCode)
            ->setException(get_class($exception));

        $this->addErrors($customResponse, $exception);

        $response = $this->responseFactory->createResponse();
        $response->getBody()->write($customResponse->getJson());

        $response = $this->withStatus($response, $exception);

        /** @var \Exception $exception */
        $this->logger->error($exception);

        return $this->withCorsHeader($request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     */
    private function withCorsHeader(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        return $response
            ->withHeader('Access-Control-Allow-Origin', $_ENV['WEB_FRONTEND_LINK'])
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader("Content-Type", "application/json");
    }

    /**
     * @param ResponseInterface       $response
     * @param Throwable|BaseException $exception
     *
     * @return ResponseInterface
     */
    private function withStatus(ResponseInterface $response, Throwable|BaseException $exception): ResponseInterface
    {
        try {
            return $response->withStatus($exception->getCode());
        } catch (\Exception) {
            return $response->withStatus(HttpResponseCodeInterface::HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param CustomResponseInterface $customResponse
     * @param Throwable $exception
     * @return CustomResponseInterface
     */
    private function addErrors(CustomResponseInterface $customResponse, Throwable $exception): CustomResponseInterface
    {
        if (!$exception instanceof BaseException) {
            $this->addTrace($customResponse, $exception);
            return $customResponse->addError([
                'message' => $exception->getMessage()
            ]);
        }

        $errors = $exception->getErrors();

        if (!$errors) {
            $this->addTrace($customResponse, $exception);
            return $customResponse->addError([
                'message' => $exception->getMessage()
            ]);
        }

        foreach ($errors as $error) {
            $customResponse->addError($error);
        }

        return $customResponse;
    }

    /**
     * Adds Trace of Error if API is in development mode
     *
     * @param CustomResponseInterface $customResponse
     * @param Throwable               $exception
     */
    private function addTrace(CustomResponseInterface $customResponse, Throwable $exception): void
    {
        if ($_ENV['API_DEBUG'] === 'development') {
            $customResponse->addError([
                'trace' => $exception->getTraceAsString()
            ]);
        }
    }
}
