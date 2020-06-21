<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hash;

use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\ArgumentsTransportInterface;

interface HashFactoryInterface
{
    public function build(ArgumentsTransportInterface $args);
}
