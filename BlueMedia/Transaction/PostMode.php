<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Transaction;

use GG\OnlinePaymentsBundle\BlueMedia\Constants\BlueMediaConst;
use GG\OnlinePaymentsBundle\BlueMedia\Hash\HashFactoryInterface;
use GG\OnlinePaymentsBundle\BlueMedia\Message\OutMessageInterface;
use GG\OnlinePaymentsBundle\Connector\ConnectorInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;

class PostMode implements ModeInterface
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

        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            Request::METHOD_POST,
            $connector->getBaseServiceUrl(),
            [
                'body' => $argsArray,
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded']
            ]
        );
        return $response->getContent();
    }
}
