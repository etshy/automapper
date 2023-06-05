<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\PropertyMapper\Mapper;

use Etshy\AutoMapper\MapperAwareInterface;
use Etshy\AutoMapper\PropertyMapper\DefaultPropertyMapper;
use Etshy\AutoMapper\PropertyMapper\MapperAwareTrait;

class ToClass extends DefaultPropertyMapper implements MapperAwareInterface
{

    use MapperAwareTrait;

    private string $destinationClass;
    private bool $sourceIsObjectArray;

    public function __construct(
        string $destinationClass,
        bool $sourceIsObjectArray = false
    ) {
        $this->destinationClass = $destinationClass;
        $this->sourceIsObjectArray = $sourceIsObjectArray;

        parent::__construct();
    }

    protected function getSourceValue(string $propertyName, $source)
    {
        $value = $this->getReader()->getPropertyValue(
            $source,
            $propertyName
        );

        return $this->sourceIsObjectArray || !$this->isCollection($value)
            ? $this->mapper->map($value, $this->destinationClass)
            : $this->mapper->mapMultiple($value, $this->destinationClass);
    }

    /**
     * @param $variable
     *
     * @return bool
     */
    private function isCollection($variable): bool
    {
        return is_iterable($variable);
    }
}
