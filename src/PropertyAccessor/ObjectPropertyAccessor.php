<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyAccessor;

use Closure;

class ObjectPropertyAccessor implements PropertyAccessorInterface
{

    public function hasProperty($object, string $propertyName): bool
    {
        if (property_exists($object, $propertyName)) {
            return true;
        }

        $properties = $this->getPropertiesName($object);

        return in_array($propertyName, $properties, true);
    }

    public function getPropertyValue($source, string $propertyName): mixed
    {
        if ($this->isPublic($source, $propertyName)) {
            return $source->$propertyName;
        }

        return $this->getPrivateValue($source, $propertyName);
    }

    public function setPropertyValue(&$destination, string $propertyName, mixed $value): void
    {
        if ($this->isPublic($destination, $propertyName)) {
            $destination->$propertyName = $value;
        }

        $this->setPrivateValue($destination, $propertyName, $value);
    }

    private function isPublic(object $object, string $property): bool
    {
        //only public props perfectly match
        return array_key_exists($property, (array)$object);
    }

    private function getPrivateValue(object $object, string $property)
    {
        //First try with common getters methods
        if (method_exists($object, "get".ucfirst($property))) {
            return $object->{"get".ucfirst($property)}();
        }
        if (method_exists($object, "is".ucfirst($property))) {
            return $object->{"is".ucfirst($property)}();
        }
        if (method_exists($object, "has".ucfirst($property))) {
            return $object->{"has".ucfirst($property)}();
        }

        $closure = Closure::bind(fn() => $this->$property ?? null, $object, $object);

        return $closure($object);
    }

    private function setPrivateValue(object &$object, string $property, mixed $value): void
    {
        //Try setter
        if (method_exists($object, "set".ucfirst($property))) {
            $object->{"set".ucfirst($property)}($value);

            return;
        }
        $reader = function & ($object, $property) {
            $closure = Closure::bind(function & () use ($property) {
                return $this->$property;
            }, $object, $object);

            return $closure->__invoke();
        };
        $propertyValue = &$reader($object, $property);
        $propertyValue = $value;
    }

    public function getPropertiesName($object): array
    {
        //Get all properties from $object and all public from parents
        $closure = Closure::bind(fn() => get_class_vars($this::class), $object, $object);
        $properties = array_keys($closure());

        //in case there is parents, grab properties from all parents!
        foreach (class_parents($object) as $parent) {
            $closure = Closure::bind(function () {
                return get_class_vars(self::class);
            }, null, $parent);

            $properties = [...$properties, ...array_keys($closure())];
        }

        return array_unique($properties);
    }
}
