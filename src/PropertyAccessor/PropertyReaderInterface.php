<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyAccessor;

interface PropertyReaderInterface
{
    public function hasProperty($object, string $propertyName): bool;

    public function getPropertyValue($source, string $propertyName): mixed;
}
