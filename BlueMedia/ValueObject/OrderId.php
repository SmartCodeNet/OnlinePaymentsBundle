<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

class OrderId implements ValueObjectInterface
{
    use SimpleValueObjectTrait;

    public static function fromNative(): OrderId
    {
        $arg = @\func_get_arg(0);
        return new static($arg ?: md5(microtime() . random_int(0, 100000)));
    }
}
