<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyAccessor;

use Etshy\AutoMapper\Exception\SourceNotIterableException;

class ArrayPropertyAccessor implements PropertyReaderInterface
{

    public function hasProperty($source, string $propertyNameName): bool
    {
        return array_key_exists($propertyNameName, $source);
    }

    public function getPropertyValue($source, string $propertyName): mixed
    {
        return $source[$propertyName] ?? null;
    }

    /**
     * @throws SourceNotIterableException
     */
    public function getPropertiesName($object): array
    {
        if (!is_array($object)) {
            throw new SourceNotIterableException();
        }

        return array_keys($object);
    }
}
