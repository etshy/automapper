<?php

declare(strict_types=1);

namespace Etshy\Tests\Models\SubClass;

class ParentWithMultiChild
{
    /**
     * @var ChildClass[]
     */
    private array $children;

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param array $children
     */
    public function setChildren(array $children): void
    {
        $this->children = $children;
    }
}
