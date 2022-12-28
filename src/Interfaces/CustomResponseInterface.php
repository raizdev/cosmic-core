<?php
namespace Orion\Framework\Interfaces;

/**
 * Interface ResponseInterface
 *
 * @package Orion\Framework\Interfaces
 */
interface CustomResponseInterface
{
    /**
     * @return string
     */
    public function getJson(): string;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param string $status
     * @return CustomResponseInterface
     */
    public function setStatus(string $status): CustomResponseInterface;

    /**
     * @return int|string
     */
    public function getCode(): int|string;

    /**
     * @param int|string $code
     *
     * @return CustomResponseInterface
     */
    public function setCode(int|string $code): CustomResponseInterface;

    /**
     * @return string
     */
    public function getException(): string;

    /**
     * @param string $exception
     * @return CustomResponseInterface
     */
    public function setException(string $exception): CustomResponseInterface;

    /**
     * @return array
     */
    public function getErrors(): array;

    /**
     * @param array $error
     * @return CustomResponseInterface
     */
    public function addError(array $error): CustomResponseInterface;

    /**
     * @return mixed
     */
    public function getData(): mixed;

    /**
     * @param mixed $data
     *
     * @return CustomResponseInterface
     */
    public function setData(mixed $data): CustomResponseInterface;
}
