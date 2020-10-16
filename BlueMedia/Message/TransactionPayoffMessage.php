<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Message;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\ArgumentsTransportInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\TransactionPayoffArguments;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Amount;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Currency;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\IntegerNumber;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\StringValue;

class TransactionPayoffMessage extends MessageAbstract implements OutMessageInterface
{
    /**
     * @var IntegerNumber|null
     */
    private $serviceId;

    /**
     * @var IntegerNumber|null
     */
    private $balancePointId;

    /**
     * @var StringValue
     */
    private $messageId;

    /**
     * @var Amount|null
     */
    private $amount;

    /**
     * @var Currency|null
     */
    private $currency;

    /**
     * @var StringValue|null
     */
    private $customerNrb;

    /**
     * @var StringValue|null
     */
    private $swiftCode;

    /**
     * @var StringValue|null
     */
    private $foreignTransferMode;

    /**
     * @var StringValue|null
     */
    private $receiverName;

    /**
     * @var StringValue|null
     */
    private $title;

    /**
     * @var StringValue|null
     */
    private $remoteRefId;

    /**
     * @var StringValue|null
     */
    private $invoiceNumber;

    /**
     * @var StringValue|null
     */
    private $plenipotentiaryId;

    private $mappedFieldsToExecute = [
        'serviceId' => BlueMediaConst::SERVICE_ID,
        'balancePointId' => BlueMediaConst::BALANCE_POINT_ID,
        'messageId' => BlueMediaConst::MESSAGE_ID,
        'amount' => BlueMediaConst::AMOUNT,
        'currency' => BlueMediaConst::CURRENCY,
        'customerNrb' => BlueMediaConst::CUSTOMER_NRB,
        'swiftCode' => BlueMediaConst::SWIFT_CODE,
        'foreignTransferMode' => BlueMediaConst::FOREIGN_TRANSFER_MODE,
        'receiverName' => BlueMediaConst::RECEIVER_NAME,
        'title' => BlueMediaConst::TITLE,
        'remoteRefId' => BlueMediaConst::REMOTE_REF_ID,
        'invoiceNumber' => BlueMediaConst::INVOICE_NUMBER,
        'plenipotentiaryId' => BlueMediaConst::PLENIPOTENTIARY_ID,
    ];

    public function __construct(
        IntegerNumber $serviceId,
        StringValue $messageId,
        Amount $amount = null,
        IntegerNumber $balancePointId = null,
        Currency $currency = null,
        StringValue $customerNrb = null,
        StringValue $swiftCode = null,
        StringValue $foreignTransferMode = null,
        StringValue $receiverName = null,
        StringValue $title = null,
        StringValue $remoteRefId = null,
        StringValue $invoiceNumber = null,
        StringValue $plenipotentiaryId = null
    ) {
        $this->serviceId = $serviceId;
        $this->balancePointId = $balancePointId;
        $this->messageId = $messageId;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->customerNrb = $customerNrb;
        $this->swiftCode = $swiftCode;
        $this->foreignTransferMode = $foreignTransferMode;
        $this->receiverName = $receiverName;
        $this->title = $title;
        $this->remoteRefId = $remoteRefId;
        $this->invoiceNumber = $invoiceNumber;
        $this->plenipotentiaryId = $plenipotentiaryId;
    }

    protected function getArgsToComputeHash(): ArgumentsTransportInterface
    {
        $args = new TransactionPayoffArguments();

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
        $args = new TransactionPayoffArguments();

        foreach ($this->mappedFieldsToExecute as $fieldLocal => $fieldExternal) {
            if ($this->{$fieldLocal} === null) {
                continue;
            }
            $args[$fieldExternal] = $this->{$fieldLocal};

        }
        return $args->toArray();
    }

    public function getServiceId(): ?IntegerNumber
    {
        return $this->serviceId;
    }

    public function getBalancePointId(): ?IntegerNumber
    {
        return $this->balancePointId;
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

    public function getCustomerNrb(): ?StringValue
    {
        return $this->customerNrb;
    }

    public function getSwiftCode(): ?StringValue
    {
        return $this->swiftCode;
    }

    public function getForeignTransferMode(): ?StringValue
    {
        return $this->foreignTransferMode;
    }

    public function getReceiverName(): ?StringValue
    {
        return $this->receiverName;
    }

    public function getTitle(): ?StringValue
    {
        return $this->title;
    }

    public function getRemoteRefId(): ?StringValue
    {
        return $this->remoteRefId;
    }

    public function getInvoiceNumber(): ?StringValue
    {
        return $this->invoiceNumber;
    }

    public function getPlenipotentiaryId(): ?StringValue
    {
        return $this->plenipotentiaryId;
    }
}
