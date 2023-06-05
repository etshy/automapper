<?php


namespace Etshy\Tests\Configuration;

use Etshy\AutoMapper\Configuration\Options;
use Etshy\AutoMapper\PropertyAccessor\ObjectPropertyAccessor;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{

    public function testShouldNullPropertiesIgnored(): void
    {
        $options = new Options();

        $options->ignoreNullProperties();
        $result = $options->shouldNullPropertiesIgnored();
        $this->assertTrue($result);

        $options->dontIgnoreNullProperties();
        $result = $options->shouldNullPropertiesIgnored();
        $this->assertFalse($result);
    }

    public function testDefaultOptions()
    {
        $options = Options::defaultOptions();
        $this->assertFalse($options->shouldNullPropertiesIgnored());
        $this->assertInstanceOf(ObjectPropertyAccessor::class, $options->getPropertyWriter());
        $this->assertInstanceOf(ObjectPropertyAccessor::class, $options->getPropertyReader());
    }
}
