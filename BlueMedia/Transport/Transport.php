<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Transport;

interface Transport
{
    public function decode($content);
    public function encode(array $array);
}
