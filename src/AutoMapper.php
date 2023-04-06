<?php

declare(strict_types=1);

namespace Etshy\AutoMapper;

use Etshy\AutoMapper\Configuration\AutoMapperConfiguration;
use Etshy\AutoMapper\Configuration\AutoMapperConfigurationInterface;
use Etshy\AutoMapper\Configuration\MappingInterface;
use Etshy\AutoMapper\Exception\MappingNotFoundException;
use Etshy\AutoMapper\Exception\SourceNotIterableException;
use Etshy\AutoMapper\Exception\UnknownSourceTypeException;

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

    /**
     * @param array|object $source
     * @param string $destination
     *
     * @return object|array|null
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function map(array|object $source, string $destination): object|array|null
    {
        $sourceName = $this->getSourceDestinationName($source);

        $mapping = $this->autoMapperConfig->getMapping($sourceName, $destination);

        if (null === $mapping) {
            throw new MappingNotFoundException();
        }

        $destinationClass = new $destination();

        return $this->doMap($source, $destinationClass, $mapping);
    }

    /**
     * @param $sourceCollection
     * @param string $targetClass
     *
     * @return array
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     * @throws SourceNotIterableException
     */
    public function mapMultiple(
        $sourceCollection,
        string $targetClass
    ): array {
        if (!is_iterable($sourceCollection)) {
            //THROW EXCEPTION
            throw new SourceNotIterableException();
        }

        $mappedResults = [];
        foreach ($sourceCollection as $source) {
            $mappedResults[] = $this->map($source, $targetClass);
        }

        return $mappedResults;
    }

    /**
     * @param array|object $source
     * @param object $destination
     *
     * @return object|array|null
     * @throws MappingNotFoundException
     * @throws UnknownSourceTypeException
     */
    public function mapToObject(array|object $source, object $destination): object|array|null
    {
        $sourceName = $this->getSourceDestinationName($source);

        $destinationName = $this->getSourceDestinationName($destination);

        $mapping = $this->autoMapperConfig->getMapping($sourceName, $destinationName);
        if (null === $mapping) {
            throw new MappingNotFoundException();
        }

        return $this->doMap($source, $destination, $mapping);
    }

    /**
     * @param array|object $source
     * @param array|object $destination
     * @param MappingInterface $mapping
     *
     * @return object|array
     */
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
     * @throws UnknownSourceTypeException
     */
    public function getSourceDestinationName(array|object $source): string
    {
        if (is_object($source)) {
            $sourceName = $source::class;
        } else {
            $sourceName = gettype($source);
        }

        return $sourceName;
    }

}
