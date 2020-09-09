<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Event;

use GG\OnlinePaymentsBundle\BlueMedia\Message\OutMessageInterface;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\StringValue;
use Symfony\Component\EventDispatcher\EventDispatcher;

class BlueMediaTransactionRefundEvent extends EventDispatcher
{
    /** @var OutMessageInterface|null  */
    protected $message;

    /** @var StringValue  */
    protected $remoteId;

    public function __construct(
        StringValue $remoteId,
        OutMessageInterface $outMessage = null
    ) {
        parent::__construct();
        $this->remoteId = $remoteId;
        $this->message = $outMessage;
    }

    public function getMessage(): ?OutMessageInterface
    {
        return $this->message;
    }

    public function getRemoteId(): StringValue
    {
        return $this->remoteId;
    }
}
