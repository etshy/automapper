<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyMapper\Mapper;

use Etshy\AutoMapper\PropertyMapper\PropertyMapperInterface;

class Ignore implements PropertyMapperInterface
{

    public function mapProperty(string $propertyName, $source, &$destination): void
    {
        //nothing
    }
}
