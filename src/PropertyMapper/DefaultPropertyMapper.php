<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyMapper;

use Etshy\AutoMapper\Configuration\Options;
use Etshy\AutoMapper\PropertyAccessor\PropertyReaderInterface;
use Etshy\AutoMapper\PropertyAccessor\PropertyWriterInterface;

class DefaultPropertyMapper implements PropertyMapperInterface
{
    protected Options $options;

    public function __construct()
    {
    }

    public function mapProperty(string $propertyName, array|object $source, array|object &$destination): void
    {
        if (!$this->canMap($propertyName, $source, $destination)) {
            // if can't map, just ignore ir
            return;
        }

        $value = $this->getSourceValue($propertyName, $source);
        $this->setDestinationValue($propertyName, $destination, $value);
    }

    protected function canMap(string $property, array|object $source, array|object $destination): bool
    {
        if (!$this->getReader()->hasProperty($source, $property)) {
            //for whatever reasons, the source doesn't have a property
            return false;
        }

        return $this->getWriter()->hasProperty($destination, $property);
    }

    protected function getSourceValue(string $propertyName, array|object $source)
    {
        return $this->getReader()->getPropertyValue($source, $propertyName);
    }

    protected function setDestinationValue(string $propertyName, array|object $destination, mixed $value): void
    {
        if ($value === null && $this->options->shouldNullPropertiesIgnored()) {
            return;
        }

        $this->getWriter()->setPropertyValue($destination, $propertyName, $value);
    }

    protected function getReader(): PropertyReaderInterface
    {
        return $this->options->getPropertyReader();
    }

    protected function getWriter(): PropertyWriterInterface
    {
        return $this->getOptions()->getPropertyWriter();
    }

    public function setOptions(Options $options): void
    {
        $this->options = $options;
    }

    public function getOptions(): Options
    {
        return $this->options;
    }
}
