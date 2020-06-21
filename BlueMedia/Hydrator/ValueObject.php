<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\Hydrator;

use GG\OnlinePaymentsBundle\BlueMedia\ValueObject\ValueObjectInterface;

class ValueObject implements HydratorInterface
{
    /** @var array  */
    private static $cachedReflections = [];

    private static function getCachedReflection($className)
    {
        if (!array_key_exists($className, self::$cachedReflections)) {
            $reflection = new \ReflectionClass($className);
            self::$cachedReflections[$className] = [
                'class' => $reflection,
                'properties' => $reflection->getProperties(),
                'constructor' => $reflection->getConstructor()
            ];
        }
        return self::$cachedReflections[$className];
    }

    private static function getCachedProperties($className)
    {
        return self::getCachedReflection($className)['properties'];
    }

    private static function getCachedConstructor($className)
    {
        return self::getCachedReflection($className)['constructor'];
    }

    private static function getCachedClass($className)
    {
        return self::getCachedReflection($className)['class'];
    }

    public function extract($object)
    {
        $properties = self::getCachedProperties(get_class($object));
        $extractArray = [];

        foreach ($properties as $property) {
            $property->setAccessible(true);

            $name = $property->getName();
            $value = $property->getValue($object);
            if (is_object($value) && $value instanceof ValueObjectInterface) {
                $extractArray[$name] = $value->toNative();
            }
        }

        return $extractArray;
    }

    public function hydrate($object, $data)
    {
        throw new \RuntimeException("Not implemented");
    }

    public function build($class, $data)
    {
        $constructor = self::getCachedConstructor($class);
        $params = $constructor->getParameters();
        $invokeParams = [];
        if (count($params) > 0) {
            foreach ($params as $param) {
                $paramName = $param->getName();
                $notRequired = $param->isDefaultValueAvailable() && $param->getDefaultValue() === null;
                $supplyParam = $data[$paramName] ?? null;
                if (is_array($supplyParam)) {
                    $supplyParam = array_values($supplyParam);
                    $supplyParam = reset($supplyParam);
                }
                if ($supplyParam !== null) {
                    $declClass = $param->getClass();
                    $paramType = ($declClass === null ? null : (string)$declClass->getName());
                    if (null === $paramType || (is_object($supplyParam) && get_class($supplyParam) === $paramType)) {
                        $invokeParams[] = $supplyParam;
                        continue;
                    }
                    $invokeParams[] = $paramType::fromNative($supplyParam);
                    continue;
                } else {
                    if ($notRequired) {
                        $invokeParams[] = null;
                        continue;
                    }
                    throw new \InvalidArgumentException("Param " . $paramName . " required and not supplied");
                }
            }
        }
        return self::getCachedClass($class)->newInstanceArgs($invokeParams);
    }
}
