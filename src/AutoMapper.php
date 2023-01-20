<?php

declare(strict_types=1);

namespace Etshy\AutoMapper;

use Etshy\AutoMapper\Configuration\AutoMapperConfiguration;
use Etshy\AutoMapper\Configuration\AutoMapperConfigurationInterface;
use Etshy\AutoMapper\Configuration\MappingInterface;

class AutoMapper implements AutoMapperInterface
{
    private AutoMapperConfigurationInterface $autoMapperConfig;

    /**
     * @param AutoMapperConfigurationInterface|null $autoMapperConfig
     */
    public function __construct(?AutoMapperConfigurationInterface $autoMapperConfig = null)
    {
        $this->autoMapperConfig = $autoMapperConfig ?? new AutoMapperConfiguration();
    }

    public static function initialize(callable $configurator): AutoMapperInterface
    {
        $mapper = new static;
        $configurator($mapper->autoMapperConfig);

        return $mapper;
    }

    public function map(array|object $source, string $destination): object|array|null
    {
        $sourceName = $this->getSourceDestinationName($source);

        $mapping = $this->autoMapperConfig->getMapping($sourceName, $destination);
        if (null === $mapping) {
            //TODO throw exception
        }

        //TODO hasCustomMapper when it's implemented
        //if hasCustomMapper then call callable custom mapper

        if ($destination === DataTypeEnum::ARRAY) {
            return $this->doMap($source, [], $mapping);
        }

        if ($mapping->getOptions()->isConstructorSkipped()) {
            $destinationClass = $mapping->skipConstructor();
        } else {
            $destinationClass = new $destination();
        }


        return $this->doMap($source, $destinationClass, $mapping);
    }

    /**
     * @param array|object $source
     * @param array|object $destination
     *
     * @return object|array|null
     */
    public function mapToObject(array|object $source, array|object $destination): object|array|null
    {
        $sourceName = $this->getSourceDestinationName($source);

        $destinationName = $this->getSourceDestinationName($destination);

        $mapping = $this->autoMapperConfig->getMapping($sourceName, $destinationName);
        if (null === $mapping) {
            //TODO throw exception
        }

        //TODO hasCustomMapper when it's implemented
        //if hasCustomMapper then call callable custom mapper

        if ($destinationName === DataTypeEnum::ARRAY) {
            return $this->doMap($source, [], $mapping);
        }

        return $this->doMap($source, $destination, $mapping);
    }

    private function doMap(array|object $source, array|object $destination, MappingInterface $mapping): object|array
    {
        $propertiesName = $mapping->getTargetPropertiesName($destination);
        foreach ($propertiesName as $propertyName) {
            $propertyMapper = $mapping->getPropertyMapperFor($propertyName);
            if ($propertyMapper instanceof MapperAwareInterface) {
                $propertyMapper->setMapper($this);
            }

            $propertyMapper->mapProperty($propertyName, $source, $destination);
        }

        return $destination;
    }

    /**
     * @param array|object $source
     *
     * @return string
     */
    public function getSourceDestinationName(array|object $source): string
    {
        if (is_object($source)) {
            $sourceName = $source::class;
        } else {
            $sourceName = gettype($source);
            if ($sourceName !== DataTypeEnum::ARRAY) {
                //TODO throw exception
            }
        }

        return $sourceName;
    }

}
