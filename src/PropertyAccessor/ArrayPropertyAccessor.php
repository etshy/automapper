<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyAccessor;

class ArrayPropertyAccessor implements PropertyAccessorInterface
{

    public function sourceHasProperty($source, string $propertyName): bool
    {
        return array_key_exists($propertyName, $source);
    }

    public function getPropertyValue($source, string $propertyName): mixed
    {
        return $source[$propertyName] ?? null;
    }

    public function destinationHasProperty($destination, string $propertyName): bool
    {
        //that's an array duh!
        return true;
    }

    public function setPropertyValue(&$destination, string $propertyName, mixed $value): void
    {
        $destination[$propertyName] = $value;
    }

    public function getPropertiesName($object): array
    {
        if (!is_array($object)) {
            //TODO EXCEPTION
        }

        return array_keys($object);
    }
}
