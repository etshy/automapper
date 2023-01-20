<?php

declare(strict_types=1);

namespace Etshy\AutoMapper;

interface MapperAwareInterface
{
    public function setMapper(AutoMapperInterface $mapper): void;
}
