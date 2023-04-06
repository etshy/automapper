<?php

declare(strict_types=1);

namespace Etshy\Tests\Models\ExtendingClass;

final class FinalClass extends AbstractClass
{
    private string $finalField;

    /**
     * @return string
     */
    public function getFinalField(): string
    {
        return $this->finalField;
    }

    /**
     * @param string $finalField
     */
    public function setFinalField(string $finalField): void
    {
        $this->finalField = $finalField;
    }

}
