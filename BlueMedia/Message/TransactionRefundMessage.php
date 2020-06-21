<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Message;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\ArgumentsTransportInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\TransactionArguments;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Amount;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Currency;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\IntegerNumber;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\StringValue;

class TransactionRefundMessage extends MessageAbstract implements OutMessageInterface
{
    /**
     * @var IntegerNumber
     */
    private $serviceId;

    /**
     * @var StringValue
     */
    private $messageId;

    /**
     * @var IntegerNumber
     */
    private $remoteId;

    /**
     * @var Amount|null
     */
    private $amount;

    /**
     * @var Currency|null
     */
    private $currency;

    private $mappedFieldsToExecute = [
        'amount' => BlueMediaConst::AMOUNT,
        'serviceId' => BlueMediaConst::SERVICE_ID,
        'messageId' => BlueMediaConst::MESSAGE_ID,
        'remoteID' => BlueMediaConst::REMOTE_ID,
        'currency' => BlueMediaConst::CURRENCY,
    ];

    public function __construct(
        IntegerNumber $serviceId,
        StringValue $messageId,
        IntegerNumber $remoteId,
        Amount $amount = null,
        Currency $currency = null
    ) {
        $this->serviceId = $serviceId;
        $this->messageId = $messageId;
        $this->remoteId = $remoteId;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    protected function getArgsToComputeHash(): ArgumentsTransportInterface
    {
        $args = new TransactionArguments();

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
        $args = new TransactionArguments();

        foreach ($this->mappedFieldsToExecute as $fieldLocal => $fieldExternal) {
            if ($this->{$fieldLocal} === null) {
                continue;
            }
            $args[$fieldExternal] = $this->{$fieldLocal};

        }
        return $args->toArray();
    }

    public function getRemoteId(): IntegerNumber
    {
        return $this->remoteId;
    }

    public function getServiceId(): IntegerNumber
    {
        return $this->serviceId;
    }

    public function getMessageId(): StringValue
    {
        return $this->messageId;
    }

    public function getAmount(): ?Amount
    {
        return $this->amount;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }
}
