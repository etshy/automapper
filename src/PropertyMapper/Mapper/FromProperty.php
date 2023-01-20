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
        if (!$this->getReader($source)->sourceHasProperty($source, $this->propertyName)) {
            //for whatever reasons, the source doesn't have a property
            return false;
        }

        return $this->getWriter($destination)->destinationHasProperty($destination, $this->propertyName);
    }

    public function mapProperty(string $propertyName, object|array $source, object|array &$destination): void
    {
        if (!$this->canMap($this->propertyName, $source, $destination)) {
            // Alternatively throw an error here.
            return;
        }
        $value = $this->getSourceValue($this->propertyName, $source);
        $this->setDestinationValue($this->propertyName, $destination, $value);
    }
}
