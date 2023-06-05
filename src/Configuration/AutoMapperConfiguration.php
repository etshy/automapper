<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\Configuration;


class AutoMapperConfiguration implements AutoMapperConfigurationInterface
{
    private array $mappings = [];

    private Options $options;

    public function __construct()
    {
        $this->options = Options::defaultOptions();
    }

    public function registerMapping(string $source, string $destination): MappingInterface
    {
        $mapping = new Mapping($source, $destination, $this);
        $this->mappings["$source|$destination"] = $mapping;

        return $mapping;
    }

    public function getMapping(string $source, string $destination): ?MappingInterface
    {
        $mapping = $this->mappings["$source|$destination"] ?? null;
        if ($mapping) {
            return $mapping;
        }

        return null;
    }

    /**
     * @return Options
     */
    public function getOptions(): Options
    {
        return $this->options;
    }

    /**
     * @param Options $options
     */
    public function setOptions(Options $options): void
    {
        $this->options = $options;
    }
}
