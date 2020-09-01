<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Message;

use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\ArgumentsTransportInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\ItnArguments;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\HashFactoryInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Hydrator\StaticHydrator;
use GG\OnlinePaymentsBundle\BlueMedia\Hydrator\ValueObject;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\CustomerData;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\DateTime;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\OrderId;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\PaymentStatus;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Amount;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Currency;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Hash;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\IntegerNumber;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\StringValue;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\ValueObjectInterface;

class ItnMessage extends MessageAbstract
{
    /**
     * @var IntegerNumber
     */
    private $serviceID;

    /**
     * @var OrderId
     */
    private $orderID;

    /**
     * @var StringValue
     */
    private $remoteID;

    /**
     * @var Amount
     */
    private $amount;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var PaymentStatus
     */
    private $paymentStatus;

    /**
     * @var DateTime
     */
    private $paymentDate;

    /**
     * @var StringValue
     */
    private $paymentStatusDetails;

    /**
     * @var IntegerNumber|null
     */
    private $gatewayID = null;

    /**
     * @var StringValue|null
     */
    private $addressIP = null;

    /**
     * @var StringValue|null
     */
    private $title = null;

//    /**
//     * @var CustomerData|null
//     */
//    private $customerData = null;

    /**
     * @var Amount
     */
    private $startAmount;

    /**
     * @var Hash
     */
    public $docHash;

    public function __construct(
        IntegerNumber $serviceID = null,
        OrderId $orderID,
        StringValue $remoteID,
        Amount $amount,
        Currency $currency,
        DateTime $paymentDate,
        PaymentStatus $paymentStatus,
        StringValue $paymentStatusDetails = null,
        IntegerNumber $gatewayID = null,
        StringValue $addressIP = null,
        StringValue $title = null,
//        CustomerData $customerData = null,
        Hash $docHash,
        Amount $startAmount = null
    ) {
        $this->serviceID = $serviceID;
        $this->orderID = $orderID;
        $this->remoteID = $remoteID;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->paymentDate = $paymentDate;
        $this->paymentStatus = $paymentStatus;
        $this->paymentStatusDetails = $paymentStatusDetails;
        $this->gatewayID = $gatewayID;
        $this->addressIP = $addressIP;
        $this->title = $title;
//        $this->customerData = $customerData;
        $this->docHash = $docHash;
        $this->startAmount = $startAmount;
    }

    protected function getArgsToComputeHash(): ArgumentsTransportInterface
    {
        $args = new ItnArguments();

        $properties = get_object_vars($this);
        unset($properties['docHash'], $properties['customerData']);

        foreach ($properties as $key => $value) {
            if (null === $value || (!is_object($value) && !$value instanceof ValueObjectInterface)) {
                continue;
            }
            $args[$key] = $value;
        }
//        if ($this->customerData !== null) {
//            $args['customerData'] = $this->customerData->toNative();
//        }

        $args['paymentDate'] = preg_replace('/[\s\-\:]+/', '', $args['paymentDate']->toNative());

        return $args;
    }

    public function isHashValid(HashFactoryInterface $hashFactory): bool
    {
        return (string)$this->docHash === (string)$this->computeHash($hashFactory);
    }

    public function toArray(): array
    {
        $properties = get_object_vars($this);
        unset($properties['docHash'], $properties['customerData']);
        $array = [];
        foreach ($properties as $propertyName => $property) {
            if (!is_object($property) && !$property instanceof ValueObjectInterface) {
                continue;
            }
            $array[$propertyName] = $property->toNative();
        }
        //zakomentowane - nie potrzebujemy danych platnika
//        $array['customerData'] = StaticHydrator::extract(ValueObject::class, $this->customerData);
        return $array;
    }
}
