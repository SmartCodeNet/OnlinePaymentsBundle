<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Transaction;

use GG\OnlinePaymentsBundle\BlueMedia\Hash\HashFactoryInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Message\OutMessageInterface;
use GG\OnlinePaymentsBundle\Connector\ConnectorInterface;

interface ModeInterface
{
    public function serve(
        ConnectorInterface $connector,
        HashFactoryInterface $hashFactory,
        OutMessageInterface $message
    );
}
