<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Message;

use GG\OnlinePaymentsBundle\BlueMedia\Hash\HashFactoryInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\ArgumentsTransportInterface;

abstract class MessageAbstract implements MessageInterface
{
    abstract protected function getArgsToComputeHash(): ArgumentsTransportInterface;

    public function computeHash(HashFactoryInterface $hashFactory)
    {
        $args = $this->getArgsToComputeHash();
        return $hashFactory->build($args);
    }
}
