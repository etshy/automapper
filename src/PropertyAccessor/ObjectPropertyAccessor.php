<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyAccessor;

use Closure;

class ObjectPropertyAccessor implements PropertyAccessorInterface
{

    public function sourceHasProperty($source, string $propertyName): bool
    {
        return $this->hasProperty($source, $propertyName);
    }

    public function getPropertyValue($source, string $propertyName): mixed
    {
        if ($this->isPublic($source, $propertyName)) {
            return $source->$propertyName;
        }

        return $this->getPrivateValue($source, $propertyName);
    }

    public function destinationHasProperty($destination, string $propertyName): bool
    {
        return $this->hasProperty($destination, $propertyName);
    }

    public function setPropertyValue(&$destination, string $propertyName, mixed $value): void
    {
        if ($this->isPublic($destination, $propertyName)) {
            $destination->$propertyName = $value;
        }

        $this->setPrivateValue($destination, $propertyName, $value);
    }

    private function hasProperty(object $object, string $property): bool
    {
        if (property_exists($object, $property)) {
            return true;
        }
        if (get_parent_class($object)) {
            return $this->hasProperty($object, $property);
        }

        return false;
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
            return $object->{"is".ucfirst($property)}();
        }
        $closure = Closure::bind(static fn() => $this->$property ?? null, $object, $object);

        return $closure($object);
    }

    private function setPrivateValue(object &$object, string $property, mixed $value): void
    {
        //Try setter
        if (method_exists($object, "set".ucfirst($property))) {
            $object->{"set".ucfirst($property)}($value);
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
        $closure = Closure::bind(fn() => get_class_vars($this::class), $object, $object);

        return array_keys($closure($object));
    }
}
