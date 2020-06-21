<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Event;

use GG\OnlinePaymentsBundle\BlueMedia\Message\MessageInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class BlueMediaMessageReceivedEvent extends EventDispatcher
{
    /**
     * @var MessageInterface|null
     */
    private $message;

    public function __construct(MessageInterface $message = null)
    {
        parent::__construct();
        $this->message = $message;
    }

    public function getMessage(): ?MessageInterface
    {
        return $this->message;
    }
}
