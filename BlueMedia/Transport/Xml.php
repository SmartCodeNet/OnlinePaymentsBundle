<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Transport;

class Xml implements Transport
{
    public function decode($content)
    {
        $xml = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);

        $array = (array)$xml->transactions->transaction;

        $array['serviceID'] = (string)$xml->serviceID;
        $array['docHash'] = (string)$xml->hash;
        if (isset($array['customerData'])) {
            $array['customerData'] = (array)$array['customerData'];
        }
        return $array;

        $array = $array['Body']['Transaction'];
        $array['currency'] = $array['amount']['currency'];
        $array['amount'] = $array['amount']['value'];
        return $array;
    }

    public function encode(array $array)
    {
        $array = $array['Confirmation'];
        $arr = [
            'serviceID' => $array['serviceID'],
            'transactionsConfirmations' => [
                'transactionConfirmed' => [
                    'orderID' => $array['orderID'],
                    'confirmation' => $array['confirmation']
                ]
            ],
            'hash' => $array['docHash']
        ];

//        $soapArray = [
//            '@attributes' => [
//                'xmlns:env' => 'http://schemas.xmlsoap.org/soap/envelope/'
//            ],
//            'env:Header' => [],
//            'env:Body' => $array
//        ];
        return ;
    }
}
