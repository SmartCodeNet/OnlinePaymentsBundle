<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hash\Algorithm;

final class Sha256 implements AlgorithmInterface
{
    public function hash(string $string): string
    {
        return hash('sha256', $string);
    }
}
