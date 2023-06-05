<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyMapper\Mapper;

use Etshy\AutoMapper\PropertyMapper\DefaultPropertyMapper;

class FromProperty extends DefaultPropertyMapper
{

    private string $propertyName;

    public function __construct(string $propertyName)
    {
        parent::__construct();
        $this->propertyName = $propertyName;
    }

    protected function canMap(string $property, object|array $source, object|array $destination): bool
    {
        if (!$this->getReader()->hasProperty($source, $this->propertyName)) {
            //for whatever reasons, the source doesn't have a property
            return false;
        }

        return $this->getWriter()->hasProperty($destination, $property);
    }

    public function mapProperty(string $propertyName, object|array $source, object|array &$destination): void
    {
        if (!$this->canMap($propertyName, $source, $destination)) {
            return;
        }

        $value = $this->getSourceValue($this->propertyName, $source);
        $this->setDestinationValue($propertyName, $destination, $value);
    }
}
