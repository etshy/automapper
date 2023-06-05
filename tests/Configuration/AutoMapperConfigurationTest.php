<?php


namespace Etshy\Tests\Configuration;

use Etshy\AutoMapper\Configuration\AutoMapperConfiguration;
use Etshy\AutoMapper\Configuration\Options;
use Etshy\AutoMapper\PropertyAccessor\ObjectPropertyAccessor;
use Etshy\AutoMapper\PropertyAccessor\SwaggerModelPropertyAccessor;
use PHPUnit\Framework\TestCase;

class AutoMapperConfigurationTest extends TestCase
{


    public function test__construct()
    {
        $config = new AutoMapperConfiguration();
        $this->assertFalse($config->getOptions()->shouldNullPropertiesIgnored());
        $this->assertInstanceOf(ObjectPropertyAccessor::class, $config->getOptions()->getPropertyWriter());
        $this->assertInstanceOf(ObjectPropertyAccessor::class, $config->getOptions()->getPropertyReader());
    }

    public function testGetOptions()
    {
        $config = new AutoMapperConfiguration();
        $options = new Options();
        $options->setPropertyWriter(new SwaggerModelPropertyAccessor());
        $options->setPropertyReader(new ObjectPropertyAccessor());
        $config->setOptions($options);

        $options = $config->getOptions();
        $this->assertInstanceOf(SwaggerModelPropertyAccessor::class, $config->getOptions()->getPropertyWriter());
        $this->assertInstanceOf(ObjectPropertyAccessor::class, $config->getOptions()->getPropertyReader());
    }
}
