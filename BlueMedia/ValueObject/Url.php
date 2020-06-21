<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

use GG\OnlinePaymentsBundle\BlueMedia\Exception\InvalidValueException;

final class Url implements ValueObjectInterface
{
    use SimpleValueObjectTrait;

    private const ERR_INVALID_URL = 'invalidUrl';

    public function __construct($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidValueException("Invalid URL", self::ERR_INVALID_URL);
        }

        $this->value = $url;
    }
}