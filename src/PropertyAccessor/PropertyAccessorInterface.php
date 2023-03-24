<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyAccessor;

interface PropertyAccessorInterface extends PropertyReaderInterface, PropertyWriterInterface
{
    public const ARRAY_ACCESSOR = 'array';
    public const OBJECT_ACCESSOR = 'object';

    /**
     * @param mixed $object
     *
     * @return array
     */
    public function getPropertiesName($object): array;
}
