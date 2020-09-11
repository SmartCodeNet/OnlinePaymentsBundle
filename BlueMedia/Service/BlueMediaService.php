<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Service;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;
use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaITNMessageConst;
use GG\OnlinePaymentsBundle\BlueMedia\Event\BlueMediaBalancePayoffEvent;
use GG\OnlinePaymentsBundle\BlueMedia\Event\BlueMediaMessageReceivedEvent;
use GG\OnlinePaymentsBundle\BlueMedia\Event\BlueMediaTransactionEvent;
use GG\OnlinePaymentsBundle\BlueMedia\Event\BlueMediaTransactionRefundEvent;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\HashFactoryInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Hydrator\StaticHydrator;
use GG\OnlinePaymentsBundle\BlueMedia\Hydrator\ValueObject;
use GG\OnlinePaymentsBundle\BlueMedia\Message\ItnMessage;
use GG\OnlinePaymentsBundle\BlueMedia\Message\ItnResponseMessage;
use GG\OnlinePaymentsBundle\BlueMedia\Message\TransactionMessage;
use GG\OnlinePaymentsBundle\BlueMedia\Message\TransactionPayoffMessage;
use GG\OnlinePaymentsBundle\BlueMedia\Message\TransactionPayoffResponseMessage;
use GG\OnlinePaymentsBundle\BlueMedia\Message\TransactionRefundMessage;
use GG\OnlinePaymentsBundle\BlueMedia\Message\TransactionRefundResponseMessage;
use GG\OnlinePaymentsBundle\BlueMedia\Transport\Transport;
use GG\OnlinePaymentsBundle\BlueMedia\Transport\Xml;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\CustomerData;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\OrderId;
use GG\OnlinePaymentsBundle\Connector\BlueMediaConnector;
use GG\OnlinePaymentsBundle\Connector\ConnectorInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Exception\InvalidHashException;
use GG\OnlinePaymentsBundle\BlueMedia\Transaction\ModeInterface;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Amount;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Currency;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Email;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\IntegerNumber;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\StringValue;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BlueMediaService
{
    /**
     * @var Transport
     */
    private static $transport;

    /**
     * @var HashFactoryInterface
     */
    private $hashFactory;

    /**
     * @var BlueMediaConnector
     */
    private $connector;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(ConnectorInterface $connector, EventDispatcherInterface $eventDispatcher)
    {
        $this->connector = $connector;
        $this->eventDispatcher = $eventDispatcher;
        self::$transport = null;
    }

    public static function getTransport(): Transport
    {
        if (null === self::$transport) {
            self::$transport = new Xml();
        }
        return self::$transport;
    }

    public static function getPaymentUrl(): string
    {
        return '/payment';
    }

    public static function getTransactionRefundUrl(): string
    {
        return '/transactionRefund';
    }

    public static function getTransactionBalancePayoffUrl(): string
    {
        return '/balancePayoff';
    }

    public function setHashFactory(HashFactoryInterface $hashFactory): self
    {
        $this->hashFactory = $hashFactory;
        return $this;
    }

    /**
     * @param ModeInterface $mode
     * @param float $amount
     * @param string|null $customerEmail
     * @param string|null $description
     * @param int|null $gatewayId
     * @param string|null $currency
     * @param string|null $orderId
     * @return string
     */
    public function makeTransaction(
        ModeInterface $mode,
        float $amount,
        string $customerEmail = null,
        string $description = null,
        int $gatewayId = null,
        string $currency = null,
        string $orderId = null
    ): string {
        $transactionMessage = new TransactionMessage(
            Amount::fromNative($amount),
            $this->connector->getServiceId(),
            $description === null ? null : StringValue::fromNative($description),
            $gatewayId === null ? null : IntegerNumber::fromNative($gatewayId),
            $currency === null ? null : Currency::fromNative($currency),
            $customerEmail === null ? null : Email::fromNative($customerEmail),
            $orderId === null ? OrderId::fromNative() : OrderId::fromNative($orderId)
        );

        $this->eventDispatcher->dispatch(
            new BlueMediaTransactionEvent(
                $transactionMessage->getOrderId(),
                $transactionMessage
            )
        );

        return $mode->serve($this->connector, $this->hashFactory, $transactionMessage);
    }

    /**
     * @param ModeInterface $mode
     * @param string $messageId
     * @param string $remoteId
     * @param float|null $amount
     * @param string|null $currency
     * @throws InvalidHashException
     */
    public function makeTransactionRefund(
        ModeInterface $mode,
        string $messageId,
        string $remoteId,
        float $amount = null,
        string $currency = null
    ): void {
        $transactionRefundMessage = new TransactionRefundMessage(
            $this->connector->getServiceId(),
            StringValue::fromNative($messageId),
            StringValue::fromNative($remoteId),
            $amount === null ? null : Amount::fromNative($amount),
            $currency === null ? null : Currency::fromNative($currency)
        );

        $response = $mode->serve($this->connector, $this->hashFactory, $transactionRefundMessage);

        /** @var TransactionRefundResponseMessage $transaction */
        $transaction = StaticHydrator::build(
            ValueObject::class,
            TransactionRefundResponseMessage::class,
            self::getTransport()->decodeTransactionBalancePayoff($response)
        );

        if (!$transaction->isHashValid($this->hashFactory)) {
            throw new InvalidHashException($transaction);
        }

        $this->eventDispatcher->dispatch(
            new BlueMediaTransactionRefundEvent(
                $transactionRefundMessage->getRemoteId(),
                $transactionRefundMessage,
                self::getTransport()->decodeToBag($response)
            )
        );
    }

    /**
     * @param $document
     * @return ItnMessage|null
     * @throws InvalidHashException
     */
    public function receiveItnResult($document): ?ItnMessage
    {
        $transaction = self::getTransport()->decode($document);

        if (isset($transaction[BlueMediaConst::CUSTOMER_DATA]) && is_array($transaction[BlueMediaConst::CUSTOMER_DATA])) {
            $transaction[BlueMediaConst::CUSTOMER_DATA] = StaticHydrator::build(
                ValueObject::class,
                CustomerData::class,
                $transaction[BlueMediaConst::CUSTOMER_DATA]
            );
        }

        /** @var ItnMessage $transaction */
        $transaction = StaticHydrator::build(ValueObject::class, ItnMessage::class, $transaction);

        if (!$transaction->isHashValid($this->hashFactory)) {
            throw new InvalidHashException($transaction);
        }

        $this->eventDispatcher->dispatch(new BlueMediaMessageReceivedEvent($transaction));

        return $transaction;
    }

    public function makeItnXmlResult(
        string $orderId,
        string $confirmation = BlueMediaITNMessageConst::CONFIRMED
    ): string {
        $responseMessage = new ItnResponseMessage(
            $this->connector->getServiceId(),
            OrderId::fromNative($orderId),
            StringValue::fromNative($confirmation)
        );

        $argsArray = $responseMessage->getArrayToExecute();

        $argsArray = array_map(
            static function ($value) {
                return (string)$value;
            },
            $argsArray
        );

        $argsArray[BlueMediaConst::HASH] = (string)$responseMessage->computeHash($this->hashFactory);

        return self::getTransport()->encode($argsArray);
    }

    /**
     * @param ModeInterface $mode
     * @param string $messageId
     * @param float|null $amount
     * @param int|null $balancePointId
     * @param string|null $currency
     * @param string|null $customerNRB
     * @param string|null $swiftCode
     * @param string|null $foreignTransferMode
     * @param string|null $receiverName
     * @param string|null $title
     * @param string|null $remoteRefID
     * @param string|null $invoiceNumber
     * @param string|null $plenipotentiaryID
     * @throws InvalidHashException
     */
    public function makeTransactionBalancePayoff(
        ModeInterface $mode,
        string $messageId,
        float $amount = null,
        int $balancePointId = null,
        string $currency = null,
        string $customerNRB = null,
        string $swiftCode = null,
        string $foreignTransferMode = null,
        string $receiverName = null,
        string $title = null,
        string $remoteRefID = null,
        string $invoiceNumber = null,
        string $plenipotentiaryID = null
    ): void {
        $transactionRefundMessage = new TransactionPayoffMessage(
            ($balancePointId !== null) ? null : $this->connector->getServiceId(),
            StringValue::fromNative($messageId),
            $amount === null ? null : Amount::fromNative($amount),
            $balancePointId === null ? null : IntegerNumber::fromNative($amount),
            $currency === null ? null : Currency::fromNative($currency),
            $customerNRB === null ? null : StringValue::fromNative($customerNRB),
            $swiftCode === null ? null : StringValue::fromNative($swiftCode),
            $foreignTransferMode === null ? null : StringValue::fromNative($foreignTransferMode),
            $receiverName === null ? null : StringValue::fromNative($receiverName),
            $title === null ? null : StringValue::fromNative($title),
            $remoteRefID === null ? null : StringValue::fromNative($remoteRefID),
            $invoiceNumber === null ? null : StringValue::fromNative($invoiceNumber),
            $plenipotentiaryID === null ? null : StringValue::fromNative($plenipotentiaryID)
        );

        $response = $mode->serve($this->connector, $this->hashFactory, $transactionRefundMessage);

        /** @var TransactionPayoffResponseMessage $transaction */
        $transaction = StaticHydrator::build(
            ValueObject::class,
            TransactionPayoffResponseMessage::class,
            self::getTransport()->decodeTransactionBalancePayoff($response)
        );

        if (!$transaction->isHashValid($this->hashFactory)) {
            throw new InvalidHashException($transaction);
        }

        $this->eventDispatcher->dispatch(
            new BlueMediaBalancePayoffEvent(
                $transactionRefundMessage,
                self::getTransport()->decodeToBag($response)
            )
        );
    }
}
