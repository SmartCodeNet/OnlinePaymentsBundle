<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

use GG\OnlinePaymentsBundle\BlueMedia\Exception\InvalidValueException;

class StringLiteral implements ValueObjectInterface
{
    use SimpleValueObjectTrait;

    private const ERR_INVALID_STRING = 'invalidString';

    public function __construct($value)
    {
        if (false === \is_string($value)) {
            throw new InvalidValueException("No value or value isn't a string", self::ERR_INVALID_STRING);
        }
        $this->value = $value;
    }
}
