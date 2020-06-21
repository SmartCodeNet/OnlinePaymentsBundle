<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Event;

use GG\OnlinePaymentsBundle\BlueMedia\Message\OutMessageInterface;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\OrderId;
use Symfony\Component\EventDispatcher\EventDispatcher;

class BlueMediaTransactionEvent extends EventDispatcher
{
    /** @var OutMessageInterface|null  */
    protected $message;

    /** @var OrderId  */
    protected $orderId;

    public function __construct(
        OrderId $orderId,
        OutMessageInterface $outMessage = null
    ) {
        parent::__construct();
        $this->orderId = $orderId;
        $this->message = $outMessage;
    }

    public function getMessage(): ?OutMessageInterface
    {
        return $this->message;
    }

    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }
}
