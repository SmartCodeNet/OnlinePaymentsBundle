<?php

namespace GG\OnlinePaymentsBundle;

use GG\OnlinePaymentsBundle\BlueMedia\Hash\HashFactory;
use GG\OnlinePaymentsBundle\BlueMedia\Service\BlueMediaService;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\IntegerNumber;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\StringValue;
use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\Url;
use GG\OnlinePaymentsBundle\Connector\BlueMediaConnector;

class PaymentsFactory
{
    public static function buildBlueMediaService(
        string $serviceUrl,
        string $serviceId,
        string $secret
    ): BlueMediaService {
        $connector = new BlueMediaConnector(
            Url::fromNative($serviceUrl),
            IntegerNumber::fromNative($serviceId),
            StringValue::fromNative($secret)
        );
        $bmService = new BlueMediaService($connector);
        $bmService->setHashFactory(new HashFactory(
            $connector->getSecret()
        ));
        return $bmService;
    }
}
