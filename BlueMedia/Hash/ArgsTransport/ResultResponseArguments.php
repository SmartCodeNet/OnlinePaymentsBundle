<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;

class ResultResponseArguments extends ArgumentsTransportAbstract
{
    protected function hashParamsOrder(): array
    {
        return [
            BlueMediaConst::SERVICE_ID,
            BlueMediaConst::ORDER_ID,
            BlueMediaConst::CONFIRMATION
        ];
    }
}
