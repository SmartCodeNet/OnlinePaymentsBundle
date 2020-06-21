<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Transaction;

use GG\OnlinePaymentsBundle\BlueMedia\Hash\HashFactoryInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Message\OutMessageInterface;
use GG\OnlinePaymentsBundle\Connector\ConnectorInterface;

class RedirectMode implements ModeInterface
{
    public function serve(
        ConnectorInterface $connector,
        HashFactoryInterface $hashFactory,
        OutMessageInterface $message
    ): string {
        $connector->getBaseServiceUrl();

        $argsArray = $message->getArrayToExecute();
        $argsArray = array_map(
            static function ($value) {
                return (string)$value;
            },
            $argsArray
        );

        $argsArray['Hash'] = (string)$message->computeHash($hashFactory);

        header("Location: " . $connector->getBaseServiceUrl() . "?" . \http_build_query($argsArray));
        return 'OK';
    }
}
