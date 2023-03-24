<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\Configuration;

use Etshy\AutoMapper\PropertyMapper\PropertyMapperInterface;

interface MappingInterface
{
    public function getTargetPropertiesName($destination): array;

    public function getPropertyMapperFor(string $propertyName): PropertyMapperInterface;

    public function forMember(string $targetPropertyName, $propertyMapper): MappingInterface;

    public function getOptions(): Options;
}
