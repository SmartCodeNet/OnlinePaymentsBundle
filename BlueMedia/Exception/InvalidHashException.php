<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Exception;

use GG\OnlinePaymentsBundle\BlueMedia\Message\MessageInterface;

use InvalidArgumentException;

class InvalidHashException extends InvalidArgumentException
{
    /** @var MessageInterface  */
    private $messageInterface;

    public function getMessageInterface(): MessageInterface
    {
        return $this->messageInterface;
    }

    public function __construct(MessageInterface $message)
    {
        $this->messageInterface = $message;
        parent::__construct("Invalid hash received");
    }
}
