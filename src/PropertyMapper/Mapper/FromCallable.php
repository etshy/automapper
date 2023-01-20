<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyMapper\Mapper;

use Etshy\AutoMapper\MapperAwareInterface;
use Etshy\AutoMapper\PropertyMapper\DefaultPropertyMapper;
use Etshy\AutoMapper\PropertyMapper\MapperAwareTrait;

class FromCallable extends DefaultPropertyMapper implements MapperAwareInterface
{
    use MapperAwareTrait;

    /**
     * @var callable
     */
    private $callable;

    public function __construct(callable $callable)
    {
        parent::__construct();
        $this->callable = $callable;
    }

    protected function canMap(string $property, object|array $source, object|array $destination): bool
    {
        return true;
    }

    protected function getSourceValue(string $propertyName, object|array $source)
    {
        return ($this->callable)($source, $this->mapper);
    }
}
