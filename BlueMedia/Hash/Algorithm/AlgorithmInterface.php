<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hash\Algorithm;

interface AlgorithmInterface
{
    public function hash(string $string): string;
}
