<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Event;

use GG\OnlinePaymentsBundle\BlueMedia\Message\OutMessageInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Response\Data\ResponseDataBag;
use Symfony\Component\EventDispatcher\EventDispatcher;

class BlueMediaBalancePayoffEvent extends EventDispatcher
{
    /** @var OutMessageInterface|null  */
    protected $message;

    /** @var ResponseDataBag|null  */
    protected $responseDataBag;

    public function __construct(
        OutMessageInterface $outMessage = null,
        ResponseDataBag $responseDataBag = null
    ) {
        parent::__construct();
        $this->message = $outMessage;
        $this->responseDataBag = $responseDataBag;
    }

    public function getMessage(): ?OutMessageInterface
    {
        return $this->message;
    }

    public function getResponseDataBag(): ?ResponseDataBag
    {
        return $this->responseDataBag;
    }
}
