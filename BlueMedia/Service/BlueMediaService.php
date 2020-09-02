<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Service;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;
use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaITNMessageConst;
use GG\OnlinePaymentsBundle\BlueMedia\Event\BlueMediaMessageReceivedEvent;
use GG\OnlinePaymentsBundle\BlueMedia\Event\BlueMediaTransactionEvent;
use GG\OnlinePaymentsBundle\BlueMedia\Event\BlueMediaTransactionRefundEvent;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\HashFactoryInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Hydrator\StaticHydrator;
use GG\OnlinePaymentsBundle\BlueMedia\Hydrator\ValueObject;
use GG\OnlinePaymentsBundle\BlueMedia\Message\ItnMessage;
use GG\OnlinePaymentsBundle\BlueMedia\Message\ItnResponseMessage;
use GG\OnlinePaymentsBundle\BlueMedia\Message\TransactionMessage;
use GG\OnlinePaymentsBundle\BlueMedia\Message\TransactionRefundMessage;
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

    public function setHashFactory(HashFactoryInterface $hashFactory): self
    {
        $this->hashFactory = $hashFactory;
        return $this;
    }

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
            $orderId === null ? null : OrderId::fromNative($orderId)
        );

        $this->eventDispatcher->dispatch(
            new BlueMediaTransactionEvent(
                $transactionMessage->getOrderId(),
                $transactionMessage
            )
        );

        return $mode->serve($this->connector, $this->hashFactory, $transactionMessage);
    }

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
            IntegerNumber::fromNative($remoteId),
            $amount === null ? null : Amount::fromNative($amount),
            $currency === null ? null : Currency::fromNative($currency)
        );

        $this->eventDispatcher->dispatch(
            new BlueMediaTransactionRefundEvent(
                $transactionRefundMessage->getRemoteId(),
                $transactionRefundMessage
            )
        );

        $mode->serve($this->connector, $this->hashFactory, $transactionRefundMessage);
    }

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

        $transaction = StaticHydrator::build(ValueObject::class, ItnMessage::class, $transaction);

        $hash = $transaction->computeHash($this->hashFactory);
        if ((string)$hash !== (string)$transaction->docHash) {
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
}
