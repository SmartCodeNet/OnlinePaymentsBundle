<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;

class TransactionRefundArguments extends ArgumentsTransportAbstract
{
    protected function hashParamsOrder(): array
    {
        return [
            BlueMediaConst::SERVICE_ID,
            BlueMediaConst::MESSAGE_ID,
            BlueMediaConst::REMOTE_ID,
            BlueMediaConst::AMOUNT,
            BlueMediaConst::CURRENCY
        ];
    }
}
