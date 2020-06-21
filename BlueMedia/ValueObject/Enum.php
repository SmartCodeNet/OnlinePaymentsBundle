<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

use GG\OnlinePaymentsBundle\BlueMedia\Exception\InvalidValueException;
use InvalidArgumentException;

abstract class Enum implements ValueObjectInterface, EnumInterface
{
    private const ERR_VAL_NOT_IN_STACK = 'valNotInStack';

    private $value;

    public function __construct($currency)
    {
        $this->value = $currency;
    }

    public static function fromNative()
    {
        try {
            return static::get($value = func_get_arg(0));
        } catch (InvalidArgumentException $e) {
            throw new InvalidValueException(
                "Value (" . $value . ") does not exist in the stack",
                self::ERR_VAL_NOT_IN_STACK
            );
        }
    }

    public function toNative()
    {
        return $this->value;
    }

    public function __toString()
    {
        return (string)$this->toNative();
    }

    /**
     * @param mixed $value
     * @return static
     */
    public static function get($value)
    {
        if (!in_array($value, static::values(), true)) {
            throw new InvalidArgumentException();
        }

        return new static($value);
    }
}