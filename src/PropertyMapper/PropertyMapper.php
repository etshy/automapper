<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyMapper;

use Etshy\AutoMapper\PropertyMapper\Mapper\FromCallable;
use Etshy\AutoMapper\PropertyMapper\Mapper\FromProperty;
use Etshy\AutoMapper\PropertyMapper\Mapper\Ignore;
use Etshy\AutoMapper\PropertyMapper\Mapper\ToClass;

class PropertyMapper
{
    public static function fromCallable(callable $callable): FromCallable
    {
        return new FromCallable($callable);
    }

    public static function ignore(): Ignore
    {
        return new Ignore();
    }

    public static function fromProperty(string $propertyName): FromProperty
    {
        return new FromProperty($propertyName);
    }


    public static function toClass(
        string $destinationClass,
        bool $sourceIsObjectArray = false
    ): ToClass {
        return new ToClass($destinationClass, $sourceIsObjectArray);
    }
}
