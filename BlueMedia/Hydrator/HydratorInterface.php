<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hydrator;

interface HydratorInterface
{
    public function extract($object);
    public function hydrate($object, $data);
    public function build($class, $data);
}
