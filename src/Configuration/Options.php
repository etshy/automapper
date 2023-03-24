<?php

declare(strict_types=1);

namespace Etshy\AutoMapper\Configuration;

class Options
{
    private bool $nullPropertiesIgnored = false;

    public function ignoreNullProperties(): self
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
