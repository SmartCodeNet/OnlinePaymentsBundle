<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hash\ArgsTransport;

abstract class ArgumentsTransportAbstract implements ArgumentsTransportInterface
{
    /**
     * @var array
     */
    protected $args = [];

    public function count(): int
    {
        return count($this->args);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->args[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->args[$offset];
    }

    abstract protected function hashParamsOrder(): array;

    public function offsetSet($offset, $value): void
    {
        $hashParamOrder = $this->hashParamsOrder();

        if ($value === null || $value === "" || empty($value)) {
            return;
        }

        if (!in_array($offset, $hashParamOrder, true)) {
            return;
        }

        $this->args[$offset] = $value;
        $this->orderByKey($this->args, $hashParamOrder);
    }

    protected function orderByKey(&$array, $paramOrder): void
    {
        uksort($array, static function ($key1, $key2) use ($paramOrder) {
            return array_search($key1, $paramOrder, true) - array_search($key2, $paramOrder, true);
        });
    }

    public function offsetUnset($offset): void
    {
        unset($this->args[$offset]);
    }

    public function toArray(): array
    {
        return $this->args;
    }
}
