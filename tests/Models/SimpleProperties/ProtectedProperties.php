<?php

declare(strict_types=1);

namespace Etshy\Tests\Models\SimpleProperties;

class ProtectedProperties
{
    protected ?string $prop1 = null;
    protected ?int $prop2 = null;
    protected ?int $prop3 = null;

    /**
     * @return string|null
     */
    public function getProp1(): ?string
    {
        return $this->prop1;
    }

    /**
     * @param string|null $prop1
     */
    public function setProp1(?string $prop1): void
    {
        $this->prop1 = $prop1;
    }

    /**
     * @return int|null
     */
    public function getProp2(): ?int
    {
        return $this->prop2;
    }

    /**
     * @param int|null $prop2
     */
    public function setProp2(?int $prop2): void
    {
        $this->prop2 = $prop2;
    }

    /**
     * @return int|null
     */
    public function getProp3(): ?int
    {
        return $this->prop3;
    }

    /**
     * @param int|null $prop3
     */
    public function setProp3(?int $prop3): void
    {
        $this->prop3 = $prop3;
    }
}
