<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyAccessor;

interface PropertyWriterInterface
{
    public function hasProperty($object, string $propertyName): bool;

    public function setPropertyValue(&$destination, string $propertyName, mixed $value): void;
}
