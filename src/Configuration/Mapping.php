<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\Configuration;

use Etshy\AutoMapper\DataTypeEnum;
use Etshy\AutoMapper\Exception\SourceNotIterableException;
use Etshy\AutoMapper\PropertyAccessor\ArrayPropertyAccessor;
use Etshy\AutoMapper\PropertyMapper\DefaultPropertyMapper;
use Etshy\AutoMapper\PropertyMapper\PropertyMapper;
use Etshy\AutoMapper\PropertyMapper\PropertyMapperInterface;

class Mapping implements MappingInterface
{
    private array $propertyMappers = [];
    private PropertyMapperInterface $defaultPropertyMapper;
    private Options $options;

    public function __construct(
        string $source,
        string $destination,
        AutoMapperConfigurationInterface $autoMapperConfiguration,
    ) {
        $this->options = clone $autoMapperConfiguration->getOptions();

        //array is only a source !
        if ($source === DataTypeEnum::ARRAY) {
            $this->options->setPropertyReader(new ArrayPropertyAccessor());
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
        return $this->options->getPropertyWriter()->getPropertiesName($destination);
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
