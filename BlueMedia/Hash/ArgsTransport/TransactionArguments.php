<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;

class TransactionArguments extends ArgumentsTransportAbstract
{
    protected function hashParamsOrder(): array
    {
        return [
            BlueMediaConst::SERVICE_ID,
            BlueMediaConst::ORDER_ID,
            BlueMediaConst::AMOUNT,
            BlueMediaConst::DESCRIPTION,
            BlueMediaConst::GATEWAY_ID,
            BlueMediaConst::CURRENCY,
            BlueMediaConst::CUSTOMER_EMAIL,
            BlueMediaConst::VALIDITY_TIME,
            BlueMediaConst::LINK_VALIDITY_TIME
        ];
    }
}
