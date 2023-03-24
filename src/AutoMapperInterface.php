<?php

declare(strict_types=1);

namespace Etshy\AutoMapper;

interface AutoMapperInterface
{
    public function map(array|object $source, string $destination);

    public function mapToObject(array|object $source, array|object $destination);

    public function mapMultiple(
        $sourceCollection,
        string $targetClass
    ): array;

}
