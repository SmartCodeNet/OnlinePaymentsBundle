<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

final class Amount extends FloatNumber
{
    public function __toString()
    {
        return trim(sprintf("%10.2f", $this->toNative()));
    }
}
