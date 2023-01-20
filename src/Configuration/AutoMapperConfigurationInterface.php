<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\Configuration;

interface AutoMapperConfigurationInterface
{
    public function registerMapping(string $source, string $destination): MappingInterface;

    public function getMapping(string $source, string $destination): ?MappingInterface;
}
