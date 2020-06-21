<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

class Language extends Enum
{
    private const PL = 'pl';
    private const EN = 'en';

    public static function fromNative()
    {
        $value = (string)func_get_arg(0);
        if ($value === "" || $value === null) {
            $value = self::PL;
        }
        return parent::fromNative($value);
    }

    public static function values(): array
    {
        return [
            self::PL,
            self::EN
        ];
    }
}