<?php

declare(strict_types=1);

namespace Etshy\Tests\Models\ExtendingClass;

abstract class AbstractClass
{
    private string $abstractField;
    public string $abstractField2;

    /**
     * @return string
     */
    public function getAbstractField(): string
    {
        return $this->abstractField;
    }

    /**
     * @param string $abstractField
     */
    public function setAbstractField(string $abstractField): void
    {
        $this->abstractField = $abstractField;
    }

    /**
     * @return string
     */
    public function getAbstractField2(): string
    {
        return $this->abstractField2;
    }

    /**
     * @param string $abstractField2
     */
    public function setAbstractField2(string $abstractField2): void
    {
        $this->abstractField2 = $abstractField2;
    }
}
