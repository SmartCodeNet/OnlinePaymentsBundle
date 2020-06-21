<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

use GG\OnlinePaymentsBundle\BlueMedia\Exception\InvalidValueException;

final class Email extends StringValue
{
    private const ERR_INVALID_EMAIL = 'invalidEmail';

    public function __construct($email)
    {
        parent::__construct($email);
        if (filter_var(trim($email), FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidValueException("Invalid e-mail address", self::ERR_INVALID_EMAIL);
        }

        $this->value = $email;
    }
}
