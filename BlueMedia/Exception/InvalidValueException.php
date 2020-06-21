<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Exception;
use InvalidArgumentException;

class InvalidValueException extends InvalidArgumentException
{
    /**
     * @var string
     */
    private $errorCode;


    public function __construct(string $message, $errorCode)
    {
        parent::__construct($message);
        $this->errorCode = $errorCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
