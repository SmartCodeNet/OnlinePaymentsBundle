<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

class DateTime implements ValueObjectInterface
{
    /**
     * @var \DateTime
     */
    protected $dateTime;

    public static function fromNative(): DateTime
    {
        return new static(@\func_get_arg(0));
    }

    public function __construct($value = null)
    {
        $this->dateTime = new \DateTime($value ?: "now");
    }

    public function getRaw(): \DateTime
    {
        return $this->dateTime;
    }

    public function toNative(): string
    {
        return $this->dateTime->format("Y-m-d H:i:s");
    }

    public function __toString()
    {
        return $this->toNative();
    }
}
