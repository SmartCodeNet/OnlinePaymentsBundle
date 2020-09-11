<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Transport;

use GG\OnlinePaymentsBundle\BlueMedia\Response\Data\ResponseDataBag;

class Xml implements Transport
{
    public function decode($content): array
    {
        $xml = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);

        $array = (array)$xml->transactions->transaction;

        $array['serviceID'] = (string)$xml->serviceID;
        $array['docHash'] = (string)$xml->hash;
        if (isset($array['customerData'])) {
            $array['customerData'] = (array)$array['customerData'];
        }
        return $array;
    }

    public function decodeTransactionBalancePayoff($content): array
    {
        $xml = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);

        return (array)$xml;
    }

    public function encode(array $array): string
    {
        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('confirmationList');
        $xml->writeElement('serviceID', $array['ServiceID']);
        $xml->startElement('transactionsConfirmations');
        $xml->startElement('transactionConfirmed');
        $xml->writeElement('orderID', $array['OrderID']);
        $xml->writeElement('confirmation', $array['confirmation']);
        $xml->endElement();
        $xml->endElement();
        $xml->writeElement('hash', $array['Hash']);
        $xml->endElement();

        return $xml->outputMemory();
    }

    public function decodeToBag(string $content): ResponseDataBag
    {
        $xml = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);

        return ResponseDataBag::instance((array)$xml);
    }
}
