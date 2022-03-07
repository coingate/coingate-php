<?php

namespace CoinGate\Exception;

use Exception;
use Throwable;

class ApiErrorException extends Exception
{
    /**
     * @var string
     */
    protected $reason;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var int
     */
    protected $httpStatus;

    /**
     * protected constructor
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    final protected function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates a new API error exception.
     *
     * @param mixed $response
     * @param int $httpStatus
     *
     * @return static
     */
    public static function factory($response, int $httpStatus): ApiErrorException
    {
        $instance = new static($response['message'] ?? null);
        $instance
            ->setReason($response['reason'] ?? null)
            ->setErrorDetails($response['errors'] ?? [])
            ->setHttpStatus($httpStatus);

        return $instance;
    }

    /**
     * Gets reason for the error.
     *
     * @return null|string
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * Sets reason for the error.
     *
     * @param string|null $reason
     * @return self
     */
    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Gets additional error details (if available).
     *
     * @return array
     */
    public function getErrorDetails(): array
    {
        return $this->errors;
    }

    /**
     * Sets additional error details.
     *
     * @param array $errors
     * @return self
     */
    public function setErrorDetails(array $errors = []): self
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Gets the HTTP status code.
     *
     * @return null|int
     */
    public function getHttpStatus(): ?int
    {
        return $this->httpStatus;
    }

    /**
     * Sets the HTTP status code.
     *
     * @param null|int $httpStatus
     * @return self
     */
    public function setHttpStatus(?int $httpStatus = null): self
    {
        $this->httpStatus = $httpStatus;

        return $this;
    }
}
