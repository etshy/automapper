<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\Configuration;

use Etshy\AutoMapper\DataTypeEnum;
use Etshy\AutoMapper\Exception\SourceNotIterableException;
use Etshy\AutoMapper\PropertyAccessor\ArrayPropertyAccessor;
use Etshy\AutoMapper\PropertyAccessor\ObjectPropertyAccessor;
use Etshy\AutoMapper\PropertyAccessor\PropertyWriterInterface;
use Etshy\AutoMapper\PropertyMapper\DefaultPropertyMapper;
use Etshy\AutoMapper\PropertyMapper\PropertyMapper;
use Etshy\AutoMapper\PropertyMapper\PropertyMapperInterface;

class Mapping implements MappingInterface
{
    private array $propertyMappers = [];
    private PropertyWriterInterface $propertyWriter;
    private PropertyMapperInterface $defaultPropertyMapper;
    private Options $options;

    public function __construct(
        string $destination
    ) {
        $this->options = new Options();

        if ($destination === DataTypeEnum::ARRAY) {
            $this->propertyWriter = new ArrayPropertyAccessor();
        } else {
            $this->propertyWriter = new ObjectPropertyAccessor();
        }

        $this->defaultPropertyMapper = new DefaultPropertyMapper();
        $this->defaultPropertyMapper->setOptions($this->options);
    }

    public function forMember(
        string $targetPropertyName,
        $propertyMapper
    ): MappingInterface {
        // If it's just a regular callback, wrap it in an operation.
        if (!$propertyMapper instanceof PropertyMapperInterface) {
            $propertyMapper = PropertyMapper::fromCallable($propertyMapper);
        }

        if (method_exists($propertyMapper, 'setOptions')) {
            $propertyMapper->setOptions($this->options);
        }

        $this->propertyMappers[$targetPropertyName] = $propertyMapper;

        return $this;
    }

    /**
     * @throws SourceNotIterableException
     */
    public function getTargetPropertiesName($destination): array
    {
        return $this->propertyWriter->getPropertiesName($destination);
    }

    public function getPropertyMapperFor(string $propertyName): PropertyMapperInterface
    {
        return $this->propertyMappers[$propertyName] ?? $this->defaultPropertyMapper;
    }

    public function getOptions(): Options
    {
        return $this->options;
    }
}
