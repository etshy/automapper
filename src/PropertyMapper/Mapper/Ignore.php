<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyMapper\Mapper;

use Etshy\AutoMapper\PropertyMapper\DefaultPropertyMapper;

class Ignore extends DefaultPropertyMapper
{
    protected function canMap(string $property, object|array $source, object|array $destination): bool
    {
        //it will be ignored anyway
        return false;
    }
}
