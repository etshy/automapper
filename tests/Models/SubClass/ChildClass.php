<?php

declare(strict_types=1);

namespace Etshy\Tests\Models\SubClass;

class ChildClass
{
    private string $field = 'value';

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField(string $field): void
    {
        $this->field = $field;
    }
}
