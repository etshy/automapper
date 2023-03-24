<?php

declare(strict_types=1);

namespace Etshy\Tests\Models\SubClass;

class ParentWithOneChild
{
    private ChildClass $childClass;

    /**
     * @return ChildClass
     */
    public function getChildClass(): ChildClass
    {
        return $this->childClass;
    }

    /**
     * @param ChildClass $childClass
     */
    public function setChildClass(ChildClass $childClass): void
    {
        $this->childClass = $childClass;
    }
}
