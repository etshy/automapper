<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyMapper;

use Etshy\AutoMapper\AutoMapperInterface;

trait MapperAwareTrait
{
    private AutoMapperInterface $mapper;

    public function setMapper(AutoMapperInterface $mapper): void
    {
        $this->mapper = $mapper;
    }
}
