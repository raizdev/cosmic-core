<?php
namespace Orion\Framework\Middleware;

use Orion\Framework\Exception\ThrottleException;
use Orion\Framework\Interfaces\CustomResponseCodeInterface;
use Orion\Framework\Interfaces\HttpResponseCodeInterface;
use Predis\Client as Predis;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ThrottleMiddleware
 *
 * @package Orion\Framework\Middleware
 */
class ThrottleMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private const LOCALE_KEY = 1;

    /**
     * @var int
     */
    private int $requests = 30;

    /**
     * @var int
     */
    private int $perSecond = 60;

    /**
     * @var string
     */
    private string $storageKey = 'rate:%s:requests';

    /**
     * ThrottleMiddleware constructor.
     *
     * @param Predis $predis
     */
    public function __construct(
        private readonly Predis $predis
    ) {}

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws ThrottleException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if (!$_ENV['THROTTLE_ENABLED']) {
            return $handler->handle($request);
        }

        if ($this->hasExceededRateLimit()) {
            throw new ThrottleException(
                'There was an issue with your request, try again',
                CustomResponseCodeInterface::RESPONSE_THROTTLE_ERROR,
                HttpResponseCodeInterface::HTTP_RESPONSE_TOO_MANY_REQUESTS
            );
        }

        $this->incrementRequestCount();

        return $handler->handle($request);
    }

    /**
     * Check if the rate limit has been exceeded.
     *
     * @return boolean
     */
    private function hasExceededRateLimit(): bool
    {
        return $this->predis->get($this->getStorageKey()) >= $this->requests;
    }

    /**
     * Increment the request count.
     *
     * @return void
     */
    private function incrementRequestCount(): void
    {
        $this->predis->incr($this->getStorageKey());
        $this->predis->expire($this->getStorageKey(), $this->perSecond);
    }

    /**
     * Set the limitations.
     *
     * @param int $requests
     * @param int $perSecond
     *
     * @return $this
     */
    public function setRateLimit(int $requests, int $perSecond): self
    {
        $this->requests = $requests;
        $this->perSecond = $perSecond;

        return $this;
    }

    /**
     * Set the storage key to be used for Redis.
     *
     * @param string $storageKey
     *
     * @return $this
     */
    public function setStorageKey(string $storageKey): self
    {
        $this->storageKey = $storageKey;

        return $this;
    }

    /**
     * Get the identifier for the Redis storage key.
     *
     * @return string
     */
    private function getStorageKey(): string
    {
        return sprintf($this->storageKey, $this->getIdentifier());
    }

    /**
     * Binds the Url with the UserIp for the Identifier
     *
     * @return mixed
     */
    private function getIdentifier(): string
    {
        return $this->determineIp() . ' URL:/' . $this->getUrl();
    }

    /**
     * Gets the Url out of the request and splits the country code
     *
     * @return string
     */
    private function getUrl(): string
    {
        $url = preg_split('/\/([a-zA-Z0-9]*\/)/', $_SERVER['REQUEST_URI']);

        return $url[self::LOCALE_KEY];
    }

    /**
     * Determines the real user ip
     *
     * @return string|null
     */
    private function determineIp(): ?string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
