<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Response\Data;

use Symfony\Component\HttpFoundation\Request;

class ResponseDataBag extends ResponseDataBagAbstract
{
    public static function createFromRequest(Request $request): ResponseDataBag
    {
        return self::instance($request->query->all());
    }
}
