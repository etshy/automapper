<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\Configuration;

use Etshy\AutoMapper\DataTypeEnum;
use Etshy\AutoMapper\PropertyAccessor\ArrayPropertyAccessor;
use Etshy\AutoMapper\PropertyAccessor\ObjectPropertyAccessor;
use Etshy\AutoMapper\PropertyAccessor\PropertyReaderInterface;
use Etshy\AutoMapper\PropertyAccessor\PropertyWriterInterface;
use Etshy\AutoMapper\PropertyMapper\DefaultPropertyMapper;
use Etshy\AutoMapper\PropertyMapper\PropertyMapper;
use Etshy\AutoMapper\PropertyMapper\PropertyMapperInterface;
use ReflectionClass;
use ReflectionException;

class Mapping implements MappingInterface
{

    private string $source;
    private string $destination;
    private array $propertyMappers = [];
    private PropertyWriterInterface $propertyWriter;
    private PropertyReaderInterface $propertyReader;
    private PropertyMapperInterface $defaultPropertyMapper;
    private Options $options;

    public function __construct(
        string $source,
        string $destination
    ) {
        $this->options = new Options();
        $this->source = $source;
        $this->destination = $destination;

        if ($source === DataTypeEnum::ARRAY) {
            $this->propertyReader = new ArrayPropertyAccessor();
        } else {
            $this->propertyReader = new ObjectPropertyAccessor();
        }

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
        $operation
    ): MappingInterface {
        // If it's just a regular callback, wrap it in an operation.
        if (!$operation instanceof PropertyMapperInterface) {
            $operation = PropertyMapper::fromCallable($operation);
        }

        $operation->setOptions($this->options);

        $this->propertyMappers[$targetPropertyName] = $operation;

        return $this;
    }

    public function getTargetPropertiesName($destination): array
    {
        return $this->propertyWriter->getPropertiesName($destination);
    }

    public function getPropertyMapperFor(string $propertyName): PropertyMapperInterface
    {
        return $this->propertyMappers[$propertyName] ?? $this->defaultPropertyMapper;
    }

    /**
     * @throws ReflectionException
     */
    public function skipConstructor(): object
    {
        //todo if possible try to not use ReflectionClass
        return (new ReflectionClass($this->destination))->newInstanceWithoutConstructor();
    }

    public function getOptions(): Options
    {
        return $this->options;
    }
}
