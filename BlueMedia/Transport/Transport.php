<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Transport;

use GG\OnlinePaymentsBundle\BlueMedia\Response\Data\ResponseDataBag;

interface Transport
{
    public function decode($content);

    public function encode(array $array);

    public function decodeToBag(string $content): ResponseDataBag;

    public function decodeTransactionBalancePayoff($content): array;
}
