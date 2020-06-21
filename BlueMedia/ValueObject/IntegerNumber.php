<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

use GG\OnlinePaymentsBundle\BlueMedia\Exception\InvalidValueException;

class IntegerNumber implements ValueObjectInterface
{
    use SimpleValueObjectTrait;

    private const ERR_NOT_INT = 'notInt';

    public function __construct($value)
    {
        if (!is_numeric($value)) {
            throw new InvalidValueException("Value is not a number", self::ERR_NOT_INT);
        }
        $this->value = (int)$value;
    }
}
