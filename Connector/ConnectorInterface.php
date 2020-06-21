<?php

namespace GG\OnlinePaymentsBundle\Connector;

use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Url;

interface ConnectorInterface
{
    public function getBaseServiceUrl(): Url;
}
