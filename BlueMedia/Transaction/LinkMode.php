<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Transaction;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\HashFactoryInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Message\OutMessageInterface;
use GG\OnlinePaymentsBundle\Connector\ConnectorInterface;

class LinkMode implements ModeInterface
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

        $argsArray[BlueMediaConst::HASH] = (string)$message->computeHash($hashFactory);

        return $connector->getBaseServiceUrl() . "?" . \http_build_query($argsArray);
    }
}
