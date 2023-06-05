<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyAccessor;

class SwaggerModelPropertyAccessor implements PropertyAccessorInterface
{
    public function getPropertiesName($object): array
    {
        return $object::attributeMap();
    }

    public function hasProperty($object, string $propertyName): bool
    {
        return in_array($propertyName, $object::attributeMap(), true);
    }

    public function getPropertyValue($source, string $propertyName): mixed
    {
        return $source->{$source::getters()[$propertyName]}();
    }

    public function setPropertyValue(&$destination, string $propertyName, mixed $value): void
    {
        $destination->{$destination::setters()[$propertyName]}($value);
    }
}
