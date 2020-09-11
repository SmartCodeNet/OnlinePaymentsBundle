<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;

class TransactionRefundResponseArguments extends ArgumentsTransportAbstract
{
    protected function hashParamsOrder(): array
    {
        return [
            BlueMediaConst::PAYOFF_SERVICE_ID,
            BlueMediaConst::PAYOFF_MESSAGE_ID,
            BlueMediaConst::PAYOFF_REMOTE_OUT_ID
        ];
    }
}
