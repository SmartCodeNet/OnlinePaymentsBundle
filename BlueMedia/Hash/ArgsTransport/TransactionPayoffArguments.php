<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;

class TransactionPayoffArguments extends ArgumentsTransportAbstract
{
    protected function hashParamsOrder(): array
    {
        return [
            BlueMediaConst::SERVICE_ID,
            BlueMediaConst::BALANCE_POINT_ID,
            BlueMediaConst::MESSAGE_ID,
            BlueMediaConst::AMOUNT,
            BlueMediaConst::CURRENCY,
            BlueMediaConst::CUSTOMER_NRB,
            BlueMediaConst::SWIFT_CODE,
            BlueMediaConst::FOREIGN_TRANSFER_MODE,
            BlueMediaConst::RECEIVER_NAME,
            BlueMediaConst::TITLE,
            BlueMediaConst::REMOTE_REF_ID,
            BlueMediaConst::INVOICE_NUMBER,
            BlueMediaConst::PLENIPOTENTIARY_ID,
        ];
    }
}
