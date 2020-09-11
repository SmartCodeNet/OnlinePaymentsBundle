<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Response\Data;

abstract class ResponseDataBagAbstract implements BagInterface
{
    /** @var DataBag  */
    private $dataBag;

    public function __construct(
        DataBag $dataBag
    ) {
        $this->dataBag = $dataBag;
    }

    public static function instance(array $parameters): self
    {
        $queryParamBag = new DataBag($parameters);

        return new static($queryParamBag);
    }

    public function getValue(string $key)
    {
        $val = $this->dataBag->getParameter($key);
        return $val ?? null;
    }
}
