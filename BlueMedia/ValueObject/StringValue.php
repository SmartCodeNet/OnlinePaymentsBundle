<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

use GG\OnlinePaymentsBundle\BlueMedia\Exception\InvalidValueException;

class StringValue extends StringLiteral
{
    private const ERR_EMPTY_VALUE = 'emptyValue';

    public function __construct($value)
    {
        parent::__construct($value);
        if ($value === "" || empty($value)) {
            throw new InvalidValueException("Value cannot be empty", self::ERR_EMPTY_VALUE);
        }
        $this->value = $value;
    }
}
