<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Message;

use GG\OnlinePaymentsBundle\BlueMedia\Hash\HashFactoryInterface;

interface MessageInterface
{
    public function computeHash(HashFactoryInterface $hashFactory);
}