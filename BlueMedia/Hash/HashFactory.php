<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hash;

use GG\OnlinePaymentsBundle\Bluemedia\Exception\InvalidValueException;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\Algorithm\AlgorithmInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\Algorithm\Sha256;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport\ArgumentsTransportInterface;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Hash;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\StringValue;

class HashFactory implements HashFactoryInterface
{
    private const ERR_NO_PARAMETERS = 'noParameters';
    private const ERR_INVALID_PARAMETERS = 'invalidParameters';
    private const ERR_INVALID_PARAMETER = 'invalidParameter';

    /**
     * @var AlgorithmInterface
     */
    private $algorithm;

    /**
     * @var StringValue
     */
    private $secret;

    /**
     * @var string
     */
    private $separator;

    public function __construct(
        StringValue $secret,
        string $separator = "|",
        AlgorithmInterface $algorithm = null
    ) {
        $this->algorithm = $algorithm ?: new Sha256();
        $this->secret = $secret;
        $this->separator = $separator;
    }

    public function setAlgorithm(AlgorithmInterface $algorithm): self
    {
        $this->algorithm = $algorithm;
        return $this;
    }

    public function build(ArgumentsTransportInterface $args): Hash
    {
        if (count($args) === 0) {
            throw new InvalidValueException("No parameters to generate hash!", self::ERR_NO_PARAMETERS);
        }

        $concatArray = [];

        foreach ($args->toArray() as $key => $value) {
            $concatArray[$key] = (string)$value;
        }

        $concatArray[] = $this->secret->toNative();

        return new Hash($this->algorithm->hash(implode($this->separator, $concatArray)));
    }
}
