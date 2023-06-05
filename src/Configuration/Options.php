<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\Configuration;

use Etshy\AutoMapper\PropertyAccessor\ObjectPropertyAccessor;
use Etshy\AutoMapper\PropertyAccessor\PropertyReaderInterface;
use Etshy\AutoMapper\PropertyAccessor\PropertyWriterInterface;

class Options
{
    private bool $nullPropertiesIgnored = false;
    private PropertyWriterInterface $propertyWriter;
    private PropertyReaderInterface $propertyReader;

    public static function defaultOptions(): Options
    {
        $options = new self();
        $options->dontIgnoreNullProperties();
        $options->setPropertyWriter(new ObjectPropertyAccessor());
        $options->setPropertyReader(new ObjectPropertyAccessor());

        return $options;
    }

    public function ignoreNullProperties(): self
    {
        $this->nullPropertiesIgnored = true;

        return $this;
    }

    public function dontIgnoreNullProperties(): self
    {
        $this->nullPropertiesIgnored = false;

        return $this;
    }

    public function shouldNullPropertiesIgnored(): bool
    {
        return $this->nullPropertiesIgnored;
    }

    /**
     * @return PropertyWriterInterface
     */
    public function getPropertyWriter(): PropertyWriterInterface
    {
        return $this->propertyWriter;
    }

    /**
     * @param PropertyWriterInterface $propertyWriter
     */
    public function setPropertyWriter(PropertyWriterInterface $propertyWriter): void
    {
        $this->propertyWriter = $propertyWriter;
    }

    /**
     * @return PropertyReaderInterface
     */
    public function getPropertyReader(): PropertyReaderInterface
    {
        return $this->propertyReader;
    }

    /**
     * @param PropertyReaderInterface $propertyReader
     */
    public function setPropertyReader(PropertyReaderInterface $propertyReader): void
    {
        $this->propertyReader = $propertyReader;
    }
}
