<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

class PaymentStatus extends Enum
{
    protected const PENDING = 'PENDING';
    protected const SUCCESS = 'SUCCESS';
    protected const FAILURE = 'FAILURE';

    public static function values(): array
    {
        return [
            self::PENDING,
            self::SUCCESS,
            self::FAILURE
        ];
    }
}
