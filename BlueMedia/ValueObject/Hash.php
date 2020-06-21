<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

use LogicException;

final class Hash extends StringValue
{
    public function toNative()
    {
        throw new LogicException("Operation not supported");
    }

    public function __toString()
    {
        return (string)$this->value;
    }
}
