<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hydrator;

class StaticHydrator
{
    /**
     * @var HydratorInterface[]
     */
    private static $hydrators = [];


    private static function getHydrator($hydratorName)
    {
        if (!array_key_exists($hydratorName, self::$hydrators)) {
            if (!class_exists($hydratorName)) {
                throw new \InvalidArgumentException("Hydrator " . $hydratorName . " does not exist");
            }
            $reflection = new \ReflectionClass($hydratorName);
            if (!$reflection->implementsInterface(HydratorInterface::class)) {
                throw new \UnexpectedValueException("Class " . $hydratorName . " does not implement HydratorInterface");
            }
            self::$hydrators[$hydratorName] = new $hydratorName;
        }
        return self::$hydrators[$hydratorName];
    }

    public static function hydrate($hydratorClass, $object, $data)
    {
        return self::getHydrator($hydratorClass)->hydrate($object, $data);
    }

    public static function extract($hydratorClass, $object)
    {
        return self::getHydrator($hydratorClass)->extract($object);
    }

    public static function build($hydratorClass, $class, $data)
    {
        return self::getHydrator($hydratorClass)->build($class, $data);
    }
}