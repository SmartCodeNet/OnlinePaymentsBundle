<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

use GG\OnlinePaymentsBundle\BlueMedia\Exception\InvalidValueException;

class FloatNumber implements ValueObjectInterface
{
    private const ERR_NOT_FLOAT = 'notFloat';

    use SimpleValueObjectTrait;

    public function __construct($value)
    {
        if (!is_numeric($value)) {
            throw new InvalidValueException("Supplied number " . $value . " is not a number", self::ERR_NOT_FLOAT);
        }
        $this->value = (float)$value;
    }
}
