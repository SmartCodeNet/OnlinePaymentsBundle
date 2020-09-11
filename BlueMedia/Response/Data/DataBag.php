<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Response\Data;

class DataBag
{
    /** @var array  */
    private $queryParameters;

    public function __construct(
        array $queryParameters = []
    ) {
        $this->queryParameters = $queryParameters;
    }

    public function getParameter(string $key)
    {
        return $this->queryParameters[$key]??null;
    }

    public function getParameters(): array
    {
        return $this->queryParameters;
    }

    public function getParametersKeys(): array
    {
        return array_keys($this->queryParameters);
    }
}
