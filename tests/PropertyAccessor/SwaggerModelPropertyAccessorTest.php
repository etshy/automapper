<?php


namespace Etshy\Tests\PropertyAccessor;

use Etshy\AutoMapper\PropertyAccessor\SwaggerModelPropertyAccessor;
use Etshy\Tests\Models\SwaggerModel\Address;
use PHPUnit\Framework\TestCase;

class SwaggerModelPropertyAccessorTest extends TestCase
{
    private SwaggerModelPropertyAccessor $accessor;

    protected function setUp(): void
    {
        $this->accessor = new SwaggerModelPropertyAccessor();
    }

    public function testGetPropertyValue()
    {
        $address = new Address();
        $address->setStreet($expected = 'street');

        $value = $this->accessor->getPropertyValue($address, 'street');
        $this->assertEquals($expected, $value);
    }

    public function testHasProperty()
    {
        $result = $this->accessor->hasProperty(new Address(), 'city');
        $this->assertTrue($result);

        $result = $this->accessor->hasProperty(new Address(), 'random');
        $this->assertFalse($result);
    }

    public function testSetPropertyValue()
    {
        $address = new Address();
        $this->accessor->setPropertyValue($address, "zip", $expected = '00000');

        $this->assertEquals($expected, $address->getZip());
    }

    public function testGetPropertiesName()
    {
        $properties = $this->accessor->getPropertiesName(new Address());
        $this->assertIsArray($properties);
        $this->assertContains("street", $properties);
        $this->assertArrayHasKey("city", $properties);
        $this->assertArrayHasKey("state", $properties);
        $this->assertArrayHasKey("zip", $properties);
    }
}
