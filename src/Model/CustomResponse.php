<?php
namespace Orion\Core\Model;

use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;

/**
 * Class Response
 *
 * @package ArOriones\Framework\Model
 */
class CustomResponse implements CustomResponseInterface
{
    /**
     * @var string
     */
    private string $status = '';

    /**
     * @var int|string
     */
    private int|string $code = 0;

    /**
     * @var string
     */
    private string $exception = '';

    /**
     * @var array
     */
    private array $errors = [];

    /**
     * @var mixed
     */
    private mixed $data;

    /**
     * Returns status as json.
     *
     * @return string
     */
    public function getJson(): string
    {
        if (!$this->exception || !$this->errors) {
            $response = $this->getSuccessResponse();
        } else {
            $response = $this->getErrorResponse();
        }

        return json_encode($response);
    }

    /**
     * Returns the success response
     *
     * @return array
     */
    private function getSuccessResponse(): array
    {
        return [
            'status' => $this->getStatus(),
            'code' => $this->getCode(),
            'data' => $this->getData()
        ];
    }

    /**
     * Returns the error response
     *
     * @return array
     */
    private function getErrorResponse(): array
    {
        return [
            'status' => $this->getStatus(),
            'code' => $this->getCode(),
            'exception' => $this->getException(),
            'errors' => $this->getErrors()
        ];
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        if (!$this->status) {
            return __('ok');
        }

        return $this->status;
    }

    /**
     * @param string $status
     * @return CustomResponseInterface
     */
    public function setStatus(string $status): CustomResponseInterface
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getCode(): int|string
    {
        if (!$this->code) {
            return HttpResponseCodeInterface::HTTP_RESPONSE_OK;
        }

        return $this->code;
    }

    /**
     * @param int|string $code
     *
     * @return CustomResponseInterface
     */
    public function setCode(int|string $code): CustomResponseInterface
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getException(): string
    {
        if (!$this->exception) {
            return '';
        }

        return $this->exception;
    }

    /**
     * @param string $exception
     * @return CustomResponseInterface
     */
    public function setException(string $exception): CustomResponseInterface
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        if (!$this->errors) {
            return [];
        }

        return $this->errors;
    }

    /**
     * @param array $error
     * @return CustomResponseInterface
     */
    public function addError(array $error): CustomResponseInterface
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): mixed
    {
        if (!$this->data) {
            return [];
        }

        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return CustomResponseInterface
     */
    public function setData(mixed $data): CustomResponseInterface
    {
        $this->data = $data;
        return $this;
    }
}
