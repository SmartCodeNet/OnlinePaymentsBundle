<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Message;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\ArgumentsTransportInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\ResponseArguments;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\OrderId;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\IntegerNumber;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\StringValue;

class ItnResponseMessage extends MessageAbstract implements OutMessageInterface
{
    /**
     * @var IntegerNumber
     */
    private $serviceId;

    /**
     * @var OrderId
     */
    private $orderId;

    /**
     * @var StringValue
     */
    private $confirmation;

    private $mappedFieldsToExecute = [
        'serviceId' => BlueMediaConst::SERVICE_ID,
        'orderId' => BlueMediaConst::ORDER_ID,
        'confirmation' => BlueMediaConst::CONFIRMATION
    ];

    public function __construct(
        IntegerNumber $serviceID,
        OrderId $orderID,
        StringValue $confirmation
    ) {
        $this->serviceId = $serviceID;
        $this->orderId = $orderID;
        $this->confirmation = $confirmation;
    }

    protected function getArgsToComputeHash(): ArgumentsTransportInterface
    {
        $args = new ResponseArguments();

        foreach ($this->mappedFieldsToExecute as $fieldLocal => $fieldExternal) {
            if ($this->{$fieldLocal} === null) {
                continue;
            }
            $args[$fieldExternal] = $this->{$fieldLocal};
        }

        return $args;
    }

    public function getArrayToExecute(): array
    {
        $args = new ResponseArguments();

        foreach ($this->mappedFieldsToExecute as $fieldLocal => $fieldExternal) {
            if ($this->{$fieldLocal} === null) {
                continue;
            }
            $args[$fieldExternal] = $this->{$fieldLocal};

        }

        return $args->toArray();
    }
}
