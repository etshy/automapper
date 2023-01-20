<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\Configuration;

class AutoMapperConfiguration implements AutoMapperConfigurationInterface
{
    private array $mappings = [];

    private array $options = [];

    public function registerMapping(string $source, string $destination): MappingInterface
    {
        $mapping = new Mapping($source, $destination);
        $this->mappings["$source|$destination"] = $mapping;
        return $mapping;
    }

    public function getMapping(string $source, string $destination): ?MappingInterface
    {
        $mapping = $this->mappings["$source|$destination"] ?? NULL;
        if ($mapping) {
            return $mapping;
        }
        return null;
    }
}
