<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyMapper;

use Etshy\AutoMapper\Configuration\Options;
use Etshy\AutoMapper\PropertyAccessor\ArrayPropertyAccessor;
use Etshy\AutoMapper\PropertyAccessor\ObjectPropertyAccessor;
use Etshy\AutoMapper\PropertyAccessor\PropertyAccessorInterface;
use Etshy\AutoMapper\PropertyAccessor\PropertyReaderInterface;
use Etshy\AutoMapper\PropertyAccessor\PropertyWriterInterface;

class DefaultPropertyMapper implements PropertyMapperInterface
{
    /**
     * @var array<PropertyReaderInterface>
     */
    private array $propertyReaders;
    /**
     * @var array<PropertyWriterInterface>
     */
    private array $propertyWriters;
    protected Options $options;

    public function __construct()
    {
        $this->propertyReaders = $this->propertyWriters = [
            PropertyAccessorInterface::ARRAY_ACCESSOR => new ArrayPropertyAccessor(),
            PropertyAccessorInterface::OBJECT_ACCESSOR => new ObjectPropertyAccessor(),
        ];
    }

    public function mapProperty(string $propertyName, array|object $source, array|object &$destination): void
    {
        if (!$this->canMap($propertyName, $source, $destination)) {
            // Alternatively throw an error here.
            return;
        }

        $value = $this->getSourceValue($propertyName, $source);
        $this->setDestinationValue($propertyName, $destination, $value);
    }

    protected function canMap(string $property, array|object $source, array|object $destination): bool
    {
        if (!$this->getReader($source)->sourceHasProperty($source, $property)) {
            //for whatever reasons, the source doesn't have a property
            return false;
        }

        return $this->getWriter($destination)->destinationHasProperty($destination, $property);
    }

    protected function getSourceValue(string $propertyName, array|object $source)
    {
        return $this->getReader($source)->getPropertyValue($source, $propertyName);
    }

    protected function setDestinationValue(string $propertyName, array|object $destination, mixed $value): void
    {
        if ($value === null && $this->options->shouldNullPropertiesIgnored()) {
            return;
        }

        $this->getWriter($destination)->setPropertyValue($destination, $propertyName, $value);
    }

    protected function getReader(array|object $object): PropertyReaderInterface
    {
        if (is_array($object)) {
            return $this->propertyReaders[PropertyAccessorInterface::ARRAY_ACCESSOR];
        }

        return $this->propertyReaders[PropertyAccessorInterface::OBJECT_ACCESSOR];
    }

    protected function getWriter(array|object $object): PropertyWriterInterface
    {
        if (is_array($object)) {
            return $this->propertyWriters[PropertyAccessorInterface::ARRAY_ACCESSOR];
        }

        return $this->propertyWriters[PropertyAccessorInterface::OBJECT_ACCESSOR];
    }

    public function setOptions(Options $options): void
    {
        $this->options = $options;
    }
}
