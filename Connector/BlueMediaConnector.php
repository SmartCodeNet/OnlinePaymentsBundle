<?php

namespace GG\OnlinePaymentsBundle\Connector;

use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\IntegerNumber;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\StringValue;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Url;

class BlueMediaConnector extends ConnectorAbstract
{
    /** @var IntegerNumber */
    protected $serviceId;
    /** @var StringValue */
    protected $secret;

    public function __construct(
        Url $serviceUrl,
        IntegerNumber $serviceId,
        StringValue $secret
    ) {
        $this->serviceId = $serviceId;
        $this->secret = $secret;
        parent::__construct($serviceUrl);
    }

    public function getServiceId(): IntegerNumber
    {
        return $this->serviceId;
    }

    public function getSecret(): StringValue
    {
        return $this->secret;
    }
}
