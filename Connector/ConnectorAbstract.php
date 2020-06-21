<?php

namespace GG\OnlinePaymentsBundle\Connector;

use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Url;

abstract class ConnectorAbstract implements ConnectorInterface
{
    /** @var Url */
    protected $serviceUrl;

    public function __construct(Url $serviceUrl)
    {
        $this->serviceUrl = $serviceUrl;
    }

    public function getBaseServiceUrl(): Url
    {
        return $this->serviceUrl;
    }
}
