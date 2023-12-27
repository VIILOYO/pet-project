<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AppHttpException
 * @package App\Exceptions
 */
class AppHttpException extends HttpException
{
    protected $message = null;
    /**
     * AppHttpException constructor.
     * @param string $message
     * @param int $statusCode
     * @param \Throwable|null $previous
     * @param array $headers
     * @param int|null $code
     */
    public function __construct(string $message = "", int $statusCode = 422, \Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        parent::__construct($statusCode, $this->message ? __($this->message) : $message, $previous, $headers, $code);
    }
}
