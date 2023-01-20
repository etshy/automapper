<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyMapper;

interface PropertyMapperInterface
{
    public function mapProperty(string $propertyName, array|object $source, array|object &$destination): void;
}
