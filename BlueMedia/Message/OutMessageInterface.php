<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Message;

interface OutMessageInterface extends MessageInterface
{
    public function getArrayToExecute();
}