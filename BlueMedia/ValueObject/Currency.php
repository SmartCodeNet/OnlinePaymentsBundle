<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

class Currency extends Enum
{
    private const PLN = 'PLN';
    private const EUR = 'EUR';

    public static function fromNative()
    {
        $value = (string)func_get_arg(0);
        if ($value === "" || $value === null) {
            $value = self::PLN;
        }
        return parent::fromNative($value);
    }

    public static function values(): array
    {
        return [
            self::PLN,
            self::EUR
        ];
    }
}