<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;

class ItnArguments extends ArgumentsTransportAbstract
{
    private static $customerData = [
        'fName',
        'lName',
        'streetName',
        'streetHouseNo',
        'streetStaircaseNo',
        'streetPremiseNo',
        'postalCode',
        'city',
        'nrb',
        'senderData'
    ];

    protected function hashParamsOrder(): array
    {
        return [
            BlueMediaConst::INT_SERVICE_ID,
            BlueMediaConst::INT_ORDER_ID,
            BlueMediaConst::INT_REMOTE_ID,
            BlueMediaConst::INT_AMOUNT,
            BlueMediaConst::INT_CURRENCY,
            BlueMediaConst::INT_GATEWAY_ID,
            BlueMediaConst::INT_PAYMENT_DATE,
            BlueMediaConst::INT_PAYMENT_STATUS,
            BlueMediaConst::INT_PAYMENT_STATUS_DETAILS,
            BlueMediaConst::INT_ADDRESS_IP,
            BlueMediaConst::INT_CUSTOMER_NUMBER,
            BlueMediaConst::INT_TITLE,
            BlueMediaConst::INT_CUSTOMER_DATA,
            BlueMediaConst::INT_VERIFICATION_STATUS
        ];
    }

    public function toArray(): array
    {
        $result = $this->args;

        if (isset($result[BlueMediaConst::CUSTOMER_DATA])) {
            $resultCustomerData = $result[BlueMediaConst::CUSTOMER_DATA];
            unset($result[BlueMediaConst::CUSTOMER_DATA]);
            $this->orderByKey($resultCustomerData, self::$customerData);

            foreach ($resultCustomerData as $cKey => $data) {
                $result[$cKey] = $data;
            }
        }
        return $result;
    }
}
