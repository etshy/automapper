<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\Configuration;

class Options
{
    private bool $constructorSkipped = true;
    private bool $nullPropertiesIgnored = false;

    public function isConstructorSkipped(): bool
    {
        return $this->constructorSkipped;
    }

    public function skipConstructor(): self
    {
        $this->constructorSkipped = true;

        return $this;
    }

    public function dontSkipConstructor(): self
    {
        $this->constructorSkipped = false;

        return $this;
    }

    public function ignoreNUllProperties(): self
    {
        $this->nullPropertiesIgnored = true;

        return $this;
    }

    public function dontIgnoreNullProperties(): self
    {
        $this->nullPropertiesIgnored = false;

        return $this;
    }

    public function shouldNullPropertiesIgnored(): bool
    {
        return $this->nullPropertiesIgnored;
    }
}
