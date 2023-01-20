<?php

declare(strict_types=1);

namespace Etshy\Tests\Models\SimpleProperties;

class PrivateProperties
{
    private ?string $prop1 = null;
    private ?int $prop2 = null;

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
}
