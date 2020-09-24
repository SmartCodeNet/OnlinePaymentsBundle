<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Message;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\ArgumentsTransportInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\TransactionArguments;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Amount;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Currency;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\DateTime;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Email;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\IntegerNumber;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\OrderId;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\StringValue;

class TransactionMessage extends MessageAbstract implements OutMessageInterface
{
    /**
     * @var Amount
     */
    private $amount;

    /**
     * @var IntegerNumber
     */
    private $serviceId;

    /**
     * @var StringValue|null
     */
    private $description;

    /**
     * @var IntegerNumber|null
     */
    private $gatewayID;

    /**
     * @var Currency|null
     */
    private $currency;

    /**
     * @var Email|null
     */
    private $customerEmail;

    /**
     * @var OrderId
     */
    private $orderId;

    /**
     * @var StringValue|null
     */
    private $validityTime;

    /**
     * @var StringValue|null
     */
    private $linkValidityTime;

    private $mappedFieldsToExecute = [
        'serviceId' => BlueMediaConst::SERVICE_ID,
        'orderId' => BlueMediaConst::ORDER_ID,
        'amount' => BlueMediaConst::AMOUNT,
        'description' => BlueMediaConst::DESCRIPTION,
        'gatewayID' => BlueMediaConst::GATEWAY_ID,
        'currency' => BlueMediaConst::CURRENCY,
        'customerEmail' => BlueMediaConst::CUSTOMER_EMAIL,
        'validityTime' => BlueMediaConst::VALIDITY_TIME,
        'linkValidityTime' => BlueMediaConst::LINK_VALIDITY_TIME,
    ];

    public function __construct(
        Amount $amount,
        IntegerNumber $serviceId,
        StringValue $description = null,
        IntegerNumber $gatewayID = null,
        Currency $currency = null,
        Email $customerEmail = null,
        OrderId $orderId = null,
        DateTime $validityTime = null,
        DateTime $linkValidityTime = null
    ) {
        $this->orderId = $orderId;
        $this->amount = $amount;
        $this->serviceId = $serviceId;
        $this->description = $description;
        $this->gatewayID = $gatewayID;
        $this->currency = $currency;
        $this->customerEmail = $customerEmail;
        $this->validityTime = $validityTime;
        $this->linkValidityTime = $linkValidityTime;
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

    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getServiceId(): IntegerNumber
    {
        return $this->serviceId;
    }

    public function getDescription(): ?StringValue
    {
        return $this->description;
    }

    public function getGatewayID(): ?IntegerNumber
    {
        return $this->gatewayID;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function getCustomerEmail(): ?Email
    {
        return $this->customerEmail;
    }

    public function getValidityTime(): ?StringValue
    {
        return $this->validityTime;
    }

    public function getLinkValidityTime(): ?StringValue
    {
        return $this->linkValidityTime;
    }
}
