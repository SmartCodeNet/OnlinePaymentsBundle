<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Message;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\ArgumentsTransportInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\TransactionPayoffResponseArguments;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\HashFactoryInterface;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Hash;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\IntegerNumber;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\StringValue;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\ValueObjectInterface;

class TransactionPayoffResponseMessage extends MessageAbstract
{
    /**
     * @var IntegerNumber
     */
    private $serviceID;

    /**
     * @var StringValue
     */
    private $messageID;

    /**
     * @var StringValue
     */
    private $remoteOutID;
    /**
     * @var Hash
     */
    public $hash;

    public function __construct(
        IntegerNumber $serviceID,
        StringValue $messageID,
        StringValue $remoteOutID,
        Hash $hash
    ) {
        $this->serviceID = $serviceID;
        $this->messageID = $messageID;
        $this->remoteOutID = $remoteOutID;
        $this->hash = $hash;
    }

    protected function getArgsToComputeHash(): ArgumentsTransportInterface
    {
        $args = new TransactionPayoffResponseArguments();

        $properties = get_object_vars($this);
        unset($properties[BlueMediaConst::HASH]);

        foreach ($properties as $key => $value) {
            if (null === $value || (!is_object($value) && !$value instanceof ValueObjectInterface)) {
                continue;
            }
            $args[$key] = $value;
        }

        return $args;
    }

    public function isHashValid(HashFactoryInterface $hashFactory): bool
    {
        return (string)$this->hash === (string)$this->computeHash($hashFactory);
    }

    public function toArray(): array
    {
        throw new \BadMethodCallException("Not implemented");
    }

    public function getServiceID(): IntegerNumber
    {
        return $this->serviceID;
    }

    public function getMessageID(): StringValue
    {
        return $this->messageID;
    }

    public function getRemoteOutID(): StringValue
    {
        return $this->remoteOutID;
    }

    public function getHash(): Hash
    {
        return $this->hash;
    }
}
