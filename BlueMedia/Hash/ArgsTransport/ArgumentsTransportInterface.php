<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport;

use ArrayAccess;
use Countable;

interface ArgumentsTransportInterface extends ArrayAccess, Countable
{
    public function toArray(): array;
}
